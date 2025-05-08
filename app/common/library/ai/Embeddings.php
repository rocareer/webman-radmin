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
use Exception;
use app\common\model\ai\KbsContent;
use function Symfony\Component\String\u;

class Embeddings
{
    /**
     * @var ?Embeddings 对象实例
     */
    protected static ?Embeddings $instance = null;

    protected array $config = [];

    /**
     * 初始化
     * @throws Throwable
     */
    public static function instance($config = []): Embeddings
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
        if (empty($config)) {
            $config = Helper::getConfig();
        }
        $this->config = array_merge($this->config, $config);
        if (!$this->config['ai_api_key']) {
            throw new Exception('向量化失败：请先配置api_key', 0, $config);
        }
    }

    /**
     * 执行文本转向量请求
     *
     * @param string|array $texts    文本内容
     * @param string       $textType query=检索优化,document=底库文档
     * @return array|bool
     */
    public function texts(string|array $texts, string $textType = 'query'): array|bool
    {
        $ai        = $this->config['ai_api_type'];
		
		
        $modelAttr = Helper::$embeddingModelAttr[$ai];
        if ($modelAttr['max_length'] != 0 && is_array($texts) && count($texts) > $modelAttr['max_length']) {
            return [
                'code' => 0,
                'msg'  => '向量模型接口限制：单次最多转换 ' . $modelAttr['max_length'] . ' 条数据',
            ];
        }

        $client = Helper::getClient();
        if ($modelAttr['api_type'] == 'ali') {
            try {
                $url      = $this->config['ai_api_url'] ?: $modelAttr['base_url'];
				
//                $response = $client->post($url . $modelAttr['api_path'], [
	            
                $response = $client->post($url, [
                    'json' => [
                        'model'      => $modelAttr['name'],
                        'input'      => [
                            'texts' => is_array($texts) ? $texts : [$texts],
                        ],
                        'parameters' => [
                            'text_type' => $textType,
                        ]
                    ],
                ]);
                $body     = $response->getBody();
				
                $data     = json_decode($body->getContents(), true);
            } catch (Throwable $e) {
                return [
                    'code' => 0,
                    'msg'  => $e->getMessage(),
                ];
            }

            return [
                'code'   => $response->getStatusCode() == 200 ? 1 : 0,
                'tokens' => isset($data['usage']) ? $data['usage']['total_tokens'] : 0,
                'data'   => isset($data['output']) ? $data['output']['embeddings'] : [],
                'model'  => $modelAttr['name'],
                'msg'    => $data['message'] ?? '',
            ];
        } elseif ($modelAttr['api_type'] == 'chatgpt') {
            try {
                $url      = $this->config['ai_api_url'] ?: $modelAttr['base_url'];
                $response = $client->post($url . $modelAttr['api_path'], [
                    'json' => [
                        'model' => $modelAttr['name'],
                        'input' => $texts,
                    ],
                ]);
                $body     = $response->getBody();
                $data     = json_decode($body->getContents(), true);
            } catch (Throwable $e) {
                return [
                    'code' => 0,
                    'msg'  => $e->getMessage(),
                ];
            }

            return [
                'code'   => $response->getStatusCode() == 200 ? 1 : 0,
                'tokens' => isset($data['usage']) ? $data['usage']['total_tokens'] : 0,
                'data'   => $data['data'] ?? [],
                'model'  => $modelAttr['name'],
                'msg'    => isset($data['error']) ? $data['error']['message'] : '',
            ];
        }

        return [
            'code' => 0,
            'msg'  => '不支持的向量模型接口',
        ];
    }

    /**
     * 从知识库获取匹配数据和匹配度
     *
     * @param string $text     被匹配文本
     * @param int    $topN     需要返回的[最高匹配]知识点条数，不传递则返回所有知识点的匹配度
     * @param string $textType 匹配方式，只阿里有：query=检索优化,document=底库文本
     * @param array  $extend   扩展查询条件（数据筛选条件） ['kbs' => 1, 'type' => 'qa', 'text_type' => 'query']
     * @return array ['tokens' => n, 'data' => [['similarity' => 0.8, 'data' => [...]], ['similarity' => 0.9, 'data' => ['id' => 1, title => '标题',...]]]]
     * @throws  Throwable
     */
    public function getTextMatchData(string $text, int $topN = 20, string $textType = 'query', array $extend = []): array
    {
		
        $embeddingData = $this->texts($text, $textType);
        if ($embeddingData['code'] == 0) {
            throw new Exception('向量转换错误：' . $embeddingData['msg']);
        }
        $returnData    = [
            'tokens' => $embeddingData['tokens'],
            'data'   => [],
        ];
        $embeddingData = $embeddingData['data']['0']['embedding'];

        // Redis
        if ($this->config['ai_work_mode'] == 'redis') {
            $redis      = Redis::instance();
            $redisCache = $redis->indexSearch($embeddingData, $topN, $extend, true);
			
            if (!$redisCache) return $returnData;
            $kbIds = [];
            foreach ($redisCache as $item) {
                $kbIds[$item['id']] = $item['vector_score'];
            }
            $contents = KbsContent::where('id', 'in', array_keys($kbIds))
                ->select();
            if ($contents->isEmpty()) return $returnData;
            foreach ($contents as $item) {
                $similarity = $kbIds[$item->id];
                if ($similarity > $this->config['ai_accurate_hit']) {
                    $item->hits = (int)$item->hits + 1;
                    $item->save();
                }
                unset($item->embedding);
                $matchData[]     = [
                    'similarity' => $similarity,
                    'mode'       => $this->config['ai_work_mode'],
                    'data'       => $item,
                ];
                $similarityArr[] = $similarity;
            }

            // 在MySQL重查了，根据得分重新排序
            array_multisort($similarityArr, SORT_DESC, SORT_NUMERIC, $matchData);
            $returnData['data'] = $matchData ?? [];
            return $returnData;
        }

        // MySQL
        $contents = KbsContent::where('status', 'usable')
            ->where(function ($query) use ($extend) {
                if (isset($extend['kbs'])) {
                    if (is_array($extend['kbs'])) {
                        foreach ($extend['kbs'] as $kb) {
                            $query->whereOr('ai_kbs_ids', 'find in set', $kb);
                        }
                    } else {
                        $extend['ai_kbs_ids'] = ['find in set', $extend['kbs']];
                        $query->where($extend);
                    }
                }
            })
            ->where(function ($query) use ($extend) {
                unset($extend['kbs']);
                $extend = array_filter($extend);
                if ($extend) $query->where($extend);
            })
            ->select();
        if ($contents->isEmpty()) return $returnData;
        foreach ($contents as $item) {
            $similarity = $this->cosineSimilarity($item->embedding, $embeddingData);
            if ($similarity > $this->config['ai_accurate_hit']) {
                $item->hits = (int)$item->hits + 1;
                $item->save();
            }
            unset($item->embedding);
            $matchData[]     = [
                'similarity' => $similarity,
                'mode'       => $this->config['ai_work_mode'],
                'data'       => $item,
            ];
            $similarityArr[] = $similarity;
        }

        // 匹配结果排序
        array_multisort($similarityArr, SORT_DESC, SORT_NUMERIC, $matchData);

        // 返回前N个
        if (isset($matchData) && $topN) {
            $matchData = array_slice($matchData, 0, $topN);
        }
        $returnData['data'] = $matchData ?? [];
        return $returnData;
    }

    /**
     * 余弦相似度
     */
    public function cosineSimilarity(array $vector1, array $vector2): float|int
    {
        $dotProduct = 0;
        $norm1      = 0;
        $norm2      = 0;

        for ($i = 0; $i < count($vector1); $i++) {
            $dotProduct += $vector1[$i] * $vector2[$i];
            $norm1      += pow($vector1[$i], 2);
            $norm2      += pow($vector2[$i], 2);
        }

        $norm1 = sqrt($norm1);
        $norm2 = sqrt($norm2);

        return $dotProduct / ($norm1 * $norm2);
    }
}