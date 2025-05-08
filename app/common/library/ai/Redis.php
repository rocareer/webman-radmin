<?php
/*
 *
 *  * // +----------------------------------------------------------------------
 *  * // | Rocareer [ ROC YOUR CAREER ]
 *  * // +----------------------------------------------------------------------
 *  * // | Copyright (c) 2014~2025 Albert@rocareer.com All rights reserved.
 *  * // +----------------------------------------------------------------------
 *  * // | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 *  * // +----------------------------------------------------------------------
 *  * // | Author: albert <Albert@rocareer.com>
 *  * // +----------------------------------------------------------------------
 *
 */

namespace app\common\library\ai;

use Throwable;
use exception;
use Redis as RedisE;


class Redis
{
    /**
     * @var ?Redis 对象实例
     */
    protected static ?Redis $instance = null;

    private ?RedisE $client = null;

    /**
     * redis配置
     */
    private array $config;

    private array $commands = [
        'json'  => [
            'del' => 'JSON.DEL',
            'get' => 'JSON.GET',
            'set' => 'JSON.SET',
        ],
        // 索引操作
        'index' => [
            'del'    => 'FT.DROPINDEX',
            'set'    => 'FT.CREATE',
            'info'   => 'FT.INFO',
            'search' => 'FT.SEARCH',
        ],
        // KEY
        'keys'  => 'KEYS',
        'del'   => 'DEL',
        'save'  => 'BGSAVE',
    ];

    /**
     * 初始化
     * @throws Throwable
     */
    public static function instance($config = []): Redis
    {
        if (is_null(self::$instance)) {
            self::$instance = new static($config);
        }
        return self::$instance;
    }

    /**
     * 构造函数
     * @throws Throwable
     */
    public function __construct($config)
    {
		
        $this->config = array_merge(radmin_config('ai.redis'), $config);
        if (is_null($this->client)) {
            $this->client = $this->initClient();
        }
    }

    /**
     * 设置json数据
     * @throws Throwable
     */
    public function jsonSet(string $key, mixed $value, string $path = '$', ?string $keyOptions = null): bool
    {
        $params = [$this->config['data_prefix'] . $key, $path, $this->encode($value)];
        if (isset($keyOptions) && in_array($keyOptions, ['NX', 'XX'])) {
            $params[] = $keyOptions;
        }
        $res = $this->client->rawCommand($this->commands['json']['set'], ...$params);
        if ($res != 'OK' && $this->config['fail_exception']) {
            throw new Exception($this->client->getLastError());
        }
        return $res == 'OK';
    }

    /**
     * 获取json数据
     * @throws Throwable
     */
    public function jsonGet(string $key, string ...$paths)
    {
        $paths  = empty($paths) ? ['$'] : $paths;
        $params = array_merge([$this->config['data_prefix'] . $key], $paths);
        return $this->client->rawCommand($this->commands['json']['get'], ...$params);
    }

    /**
     * 获取json数据并解码
     * @throws Throwable
     */
    public function decodeJsonGet(string $key, string ...$paths): mixed
    {
        $res = $this->jsonGet($key, ...$paths);
        return json_decode($res, true);
    }

    /**
     * 删除json数据
     * @throws Throwable
     */
    public function jsonDel(string $key, string $path = '$'): int
    {
        return $this->client->rawCommand($this->commands['json']['del'], $this->config['data_prefix'] . $key, $path);
    }

    /**
     * 删除索引
     * @throws Throwable
     */
    public function indexDel()
    {
        if ($this->indexIsSet()) {
            return $this->client->rawCommand($this->commands['index']['del'], $this->config['index_name']);
        }
        return true;
    }

    /**
     * 设置索引
     * @throws Throwable
     */
    public function indexSet(): bool
    {
        $sysConfig = Helper::getConfig();
        $params    = [
            $this->config['index_name'],
            'ON',
            'JSON',
            'PREFIX',
            '1',
            $this->config['data_prefix'],
            'SCHEMA',
            // 知识库ID
            '$.ai_kbs_ids',
            'AS',
            'kbs',
            'TAG',
            'SEPARATOR',
            ',',
            // 知识点类型
            '$.type',
            'AS',
            'type',
            'TEXT',
            'WEIGHT',
            '1.0',
            'NOSTEM',
            // text_type
            '$.text_type',
            'AS',
            'text_type',
            'TEXT',
            'WEIGHT',
            '1.0',
            'NOSTEM',
            // 向量
            '$.embedding',
            'AS',
            'vector',
            'VECTOR',
            $sysConfig['ai_vector_index'],// 索引类型:FLAT=蛮力,HNSW=分层可导航小世界
            $sysConfig['ai_vector_index'] == 'FLAT' ? 10 : 16,
            
            'TYPE',
            Helper::$embeddingModelAttr[$sysConfig['ai_api_type']]['vector_type'],// 向量类型:FLOAT32,FLOAT64
            'DIM',
            Helper::$embeddingModelAttr[$sysConfig['ai_api_type']]['vector_dim'],// 向量维度
            'DISTANCE_METRIC',
            $sysConfig['ai_vector_similarity'],// 相似性算法
            'INITIAL_CAP',
            $sysConfig['ai_initial_cap'],// 初始索引内存大小
        ];
	    
	   
        if ($sysConfig['ai_vector_index'] == 'FLAT') {
            $params[] = 'BLOCK_SIZE';
            $params[] = $sysConfig['ai_flat_block_size'];
        } elseif ($sysConfig['ai_vector_index'] == 'HNSW') {
            $params[] = 'M';
            $params[] = $sysConfig['ai_hnsw_m'];
            $params[] = 'EF_CONSTRUCTION';
            $params[] = $sysConfig['ai_hnsw_ef_construction'];
            $params[] = 'EF_RUNTIME';
            $params[] = $sysConfig['ai_hnsw_knn_ef_runtime'];
            $params[] = 'EPSILON';
            $params[] = $sysConfig['ai_hnsw_epsilon'];
        }

        $res = $this->client->rawCommand($this->commands['index']['set'], ...$params);
        if ($res != 'OK' && $this->config['fail_exception']) {
            throw new Exception($res);
        }
        return $res == 'OK';
    }

    /**
     * 索引是否存在
     * @throws Throwable
     */
    public function indexIsSet(): bool
    {
        try {
            $this->client->rawCommand($this->commands['index']['info'], $this->config['index_name']);
        } catch (Throwable $e) {
            if ($e->getMessage() == 'Unknown index name') {
                return false;
            }
        }
        return true;
    }

    /**
     * 索引搜索
     * @param array $embedding 向量数组
     * @param int   $topN      返回前多少条数据
     * @param array $extend    扩展查询条件（数据筛选条件） ['kbs' => 1, 'type' => 'qa', 'text_type' => 'query']
     * @param bool  $noContent 是否只返回匹配记录的ID号
     * @return array
     * @throws Throwable
     */
    public function indexSearch(array $embedding, int $topN = 20, array $extend = [], bool $noContent = false): array
    {
	    
	    
//	    $data = [
//		    'id' => 1,
//		    'embedding' => [0.1, 0.2, 0.3], // 示例向量
//		    'type' => 'qa',
//		    'text_type' => 'query',
//		    'ai_kbs_ids' => [101]
//	    ];
//	    $this->jsonSet('test_key', $data);
	    $sysConfig = Helper::getConfig();
	    $expectedDim = Helper::$embeddingModelAttr[$sysConfig['ai_api_type']]['vector_dim'];
	    
	    // 验证传入向量维度
	    if (count($embedding) != $expectedDim) {
		    throw new Exception("向量维度不匹配 (当前: " . count($embedding) . ", 需: $expectedDim)");
	    }
        $sysConfig = Helper::getConfig();
        $format    = Helper::$embeddingModelAttr[$sysConfig['ai_api_type']]['vector_type'] == 'FLOAT32' ? 'f' : 'd';
	    
	   
        $queryStr = '';
        if ($extend) {
            if (!empty($extend['kbs'])) {
                if (is_array($extend['kbs'])) {
                    $extend['kbs'] = implode('|', $extend['kbs']);
                }
                $queryStr .= '@kbs:{' . $extend['kbs'] . '} ';
            }
            if (!empty($extend['type'])) {
                $queryStr .= "@type:{$extend['type']} ";
            }
            if (!empty($extend['text_type'])) {
                $queryStr .= "@text_type:{$extend['text_type']} ";
            }
        }
		
        if ($queryStr) {
            $knn = '(' . trim($queryStr) . ')=>[KNN ' . $topN . ' @vector $query_vec AS vector_score]';
        } else {
            $knn = '*=>[KNN ' . $topN . ' @vector $query_vec AS vector_score]';
        }

        $params   = [
            $this->config['index_name'],
            $knn,
            'SORTBY',
            'vector_score',
            'ASC',
            'DIALECT',
            '2',
            'PARAMS',
            '2',
            'query_vec',
            pack("$format*", ...$embedding),
        ];
        $params[] = 'RETURN';
        if ($noContent) {
            $params[] = 4;
            $params[] = '$.id';
            $params[] = 'AS';
            $params[] = 'id';
        } else {
            $params[] = 7;
            $params[] = '$.id';
            $params[] = 'AS';
            $params[] = 'id';
            $params[] = '$.content';
            $params[] = 'AS';
            $params[] = 'content';
        }
        $params[] = 'vector_score';

        try {
            $cache = $this->client->rawCommand($this->commands['index']['search'], ...$params);
			
        } catch (Throwable $e) {
            throw new Exception($e->getMessage());
        }
        if ($cache[0] == 0) return [];

        // 数据提取
        $data = [];
        $keys = ['vector_score', 'id', 'content'];
        foreach ($cache as $item) {
            if (is_array($item)) {
                $cacheData = [];
                foreach ($item as $key => $keyName) {
                    if (in_array($keyName, $keys)) {
                        if ($keyName == 'vector_score') {
                            $cacheData[$keyName] = 1 - $item[$key + 1];
                        } else {
                            $cacheData[$keyName] = $item[$key + 1];
                        }
                    }
                }
                $data[] = $cacheData;
            }
        }
        return $data;
    }

    /**
     * 获取以保存的知识点缓存keys
     * @throws Throwable
     */
    public function jsonKeys()
    {
        return $this->client->rawCommand($this->commands['keys'], $this->config['data_prefix'] . '*');
    }

    /**
     * 批量删除key
     * @throws Throwable
     */
    public function keysDel(string|array $key)
    {
        if (is_array($key)) {
            return $this->client->rawCommand($this->commands['del'], ...$key);
        }
        return $this->client->rawCommand($this->commands['del'], $key);
    }

    /**
     * 在后台持久化缓存数据
     * @throws Throwable
     */
    public function save(): bool|string
    {
        $res = $this->client->rawCommand($this->commands['save']);
        return $res == 'OK' ? true : $res;
    }

    /**
     * 数组转json
     * @throws Throwable
     */
    public function encode($value): string
    {
        $json = json_encode($value);
        if (false === $json || json_last_error() !== JSON_ERROR_NONE && $this->config['fail_exception']) {
            throw new Exception(sprintf('json编码失败，错误：%s', json_last_error_msg()));
        }
        return $json;
    }

    /**
     * 获取最后一个错误
     * @throws Throwable
     */
    public function getLastError(): ?string
    {
        return $this->client->getLastError();
    }

    public function closeClient(): bool
    {
        return $this->client->close();
    }

    /**
     * 获取AI Redis配置
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    public function getClient(): ?RedisE
    {
        return $this->client;
    }

    /**
     * 使用当前对象配置初始化 redis 链接实例
     * @throws Throwable
     */
    public function initClient(): ?RedisE
    {
        if (!extension_loaded('redis') && $this->config['fail_exception']) {
            throw new Exception('未安装redis扩展');
        }

        $client = new RedisE;
        if ($this->config['persistent']) {
            $client->pconnect($this->config['host'], $this->config['port'], $this->config['timeout'], 'persistent_id_' . $this->config['select']);
        } else {
            $client->connect($this->config['host'], $this->config['port'], $this->config['timeout']);
        }
        if ('' != $this->config['password']) {
            $client->auth($this->config['password']);
        }

        if (false !== $this->config['select']) {
            $client->select($this->config['select']);
        }
        return $client;
    }
}