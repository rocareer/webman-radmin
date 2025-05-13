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

use plugin\radmin\extend\ba\Date;
use Throwable;
use GuzzleHttp\Client;
use app\common\model\ai\Config;
use app\common\model\ai\Session;

class Helper
{
    private static ?Client $client = null;

    /**
     * 知识库系统配置
     */
    private static array $config = [];

    /**
     * 聊天模型的相关属性
     */
    public static array $chatModelAttr = [
        'qwen-turbo'    => [
            'name'                   => '阿里千问-turbo-1000k',
            // 上下文tokens总数
            'sum'                    => 1000000,
            // 模型预留了tokens用于输出
            'reserve'                => 2000,
            // 模型接口URL
            'api_url'                => 'https://dashscope.aliyuncs.com/api/v1/services/aigc/text-generation/generation',
            // 模型接口类型:ali=DashScope,chatgpt=OpenAI兼容
            'api_type'               => 'ali',
            // 模型不支持的参数
            'unsupported_parameters' => ['ai_frequency_penalty'],
        ],
        'qwen-plus'     => [
            'name'                   => '阿里千问-plus-131k',
            'sum'                    => 131072,
            'reserve'                => 2000,
            'api_url'                => 'https://dashscope.aliyuncs.com/api/v1/services/aigc/text-generation/generation',
            'api_type'               => 'ali',
            'unsupported_parameters' => ['ai_frequency_penalty'],
        ],
        'qwen-max'      => [
            'name'                   => '阿里千问-max-30k',
            'sum'                    => 32768,
            'reserve'                => 2000,
            'api_url'                => 'https://dashscope.aliyuncs.com/api/v1/services/aigc/text-generation/generation',
            'api_type'               => 'ali',
            'unsupported_parameters' => ['ai_frequency_penalty'],
        ],
        'qwen-long'     => [
            'name'                   => '阿里千问-long-10000k',
            'sum'                    => 10000000,
            'reserve'                => 2000,
            'api_url'                => 'https://dashscope.aliyuncs.com/api/v1/services/aigc/text-generation/generation',
            'api_type'               => 'ali',
            'unsupported_parameters' => ['ai_frequency_penalty'],
        ],
        'gpt-3.5-turbo' => [
            'name'                   => 'ChatGPT-3.5-turbo-16k',
            'sum'                    => 16385,
            'reserve'                => 0,
            'api_url'                => 'https://api.openai.com/v1/chat/completions',
            'api_type'               => 'chatgpt',
            'unsupported_parameters' => ['top_k', 'ai_enable_search'],
        ],
        'gpt-4'         => [
            'name'                   => 'ChatGPT-4.0-8k',
            'sum'                    => 8192,
            'reserve'                => 0,
            'api_url'                => 'https://api.openai.com/v1/chat/completions',
            'api_type'               => 'chatgpt',
            'unsupported_parameters' => ['top_k', 'ai_enable_search'],
        ],
        'gpt-4-turbo'   => [
            'name'                   => 'ChatGPT-4.0-turbo-128k',
            'sum'                    => 128000,
            'reserve'                => 0,
            'api_url'                => 'https://api.openai.com/v1/chat/completions',
            'api_type'               => 'chatgpt',
            'unsupported_parameters' => ['top_k', 'ai_enable_search'],
        ],
        'gpt-4o'        => [
            'name'                   => 'ChatGPT-4.0-omni-128k',
            'sum'                    => 128000,
            'reserve'                => 0,
            'api_url'                => 'https://api.openai.com/v1/chat/completions',
            'api_type'               => 'chatgpt',
            'unsupported_parameters' => ['top_k', 'ai_enable_search'],
        ],
        'gpt-4o-mini'   => [
            'name'                   => 'ChatGPT-4.0-omni-mini-128k',
            'sum'                    => 128000,
            'reserve'                => 0,
            'api_url'                => 'https://api.openai.com/v1/chat/completions',
            'api_type'               => 'chatgpt',
            'unsupported_parameters' => ['top_k', 'ai_enable_search'],
        ],
        'deepseek-r1'   => [
            'name'                   => 'DeepSeek-R1-65k',
            'sum'                    => 65792,
            'reserve'                => 0,
            'api_url'                => 'https://dashscope.aliyuncs.com/compatible-mode/v1/chat/completions',
            'api_type'               => 'chatgpt',
            'unsupported_parameters' => ['ai_system_prompt', 'ai_enable_search', 'temperature', 'top_p', 'top_k', 'ai_presence_penalty', 'ai_frequency_penalty'],
        ],
        'deepseek-v3'   => [
            'name'                   => 'DeepSeek-V3-65k',
            'sum'                    => 65792,
            'reserve'                => 0,
            'api_url'                => 'https://dashscope.aliyuncs.com/compatible-mode/v1/chat/completions',
            'api_type'               => 'chatgpt',
            'unsupported_parameters' => ['ai_enable_search', 'ai_frequency_penalty'],
        ],
    ];

    /**
     * 向量化模型的相关属性
     */
    public static array $embeddingModelAttr = [
        // 阿里基础文本向量模型
        'ali'           => [
            // 模型接口类型:ali=DashScope,chatgpt=OpenAI兼容
            'api_type'    => 'ali',
            'title'       => '阿里灵积-v1',
            'name'        => 'text-embedding-v1',
            'text_type'   => true, // 支持 query 和 document 两种方式来对文本进行向量化
            'max_tokens'  => 2048, // 单条数据最大长度
            'max_length'  => 25, // 单次请求最大数据条数
            'base_url'    => 'https://dashscope.aliyuncs.com/api/v1',// 接口基础URL，可在后台修改
            'api_path'    => '/services/embeddings/text-embedding/text-embedding',
            'vector_type' => 'FLOAT32',// 向量数据类型
            'vector_dim'  => 1536,// 向量纬度
        ],
        'ali_v2'        => [
            'api_type'    => 'ali',
            'title'       => '阿里灵积-v2',
            'name'        => 'text-embedding-v2',
            'text_type'   => true, // 支持 query 和 document 两种方式来对文本进行向量化
            'max_tokens'  => 2048, // 单条数据最大长度
            'max_length'  => 25, // 单次请求最大数据条数
            'base_url'    => 'https://dashscope.aliyuncs.com/api/v1',// 接口基础URL，可在后台修改
            'api_path'    => '/services/embeddings/text-embedding/text-embedding',
            'vector_type' => 'FLOAT32',// 向量数据类型
            'vector_dim'  => 1536,// 向量纬度
        ],
        'ali_v3'        => [
            'api_type'    => 'ali',
            'title'       => '阿里灵积-v3',
            'name'        => 'text-embedding-v3',
            'text_type'   => false, // 支持 query 和 document 两种方式来对文本进行向量化
            'max_tokens'  => 8192, // 单条数据最大长度
            'max_length'  => 10, // 单次请求最大数据条数
            'base_url'    => 'https://dashscope.aliyuncs.com/api/v1',// 接口基础URL，可在后台修改
            'api_path'    => '/services/embeddings/text-embedding/text-embedding',
            'vector_type' => 'FLOAT32',// 向量数据类型
            'vector_dim'  => 1024,// 向量纬度
        ],
        // chatgpt 基础文本向量模型
        'chatgpt'       => [
            'api_type'    => 'chatgpt',
            'title'       => 'ChatGPT-v2',
            'name'        => 'text-embedding-ada-002',
            'text_type'   => false,
            'max_tokens'  => 8191,
            'max_length'  => 0, // max_length 未知
            'base_url'    => 'https://api.openai.com/v1',
            'api_path'    => '/embeddings',
            'vector_type' => 'FLOAT32',
            'vector_dim'  => 1536,
        ],
        'chatgpt_small' => [
            'api_type'    => 'chatgpt',
            'title'       => 'ChatGPT-v3-small',
            'name'        => 'text-embedding-3-small',
            'text_type'   => false,
            'max_tokens'  => 8191,
            'max_length'  => 0, // max_length 未知
            'base_url'    => 'https://api.openai.com/v1',
            'api_path'    => '/embeddings',
            'vector_type' => 'FLOAT32',
            'vector_dim'  => 1536,
        ],
        'chatgpt_large' => [
            'api_type'    => 'chatgpt',
            'title'       => 'ChatGPT-v3-large',
            'name'        => 'text-embedding-3-large',
            'text_type'   => false,
            'max_tokens'  => 8191,
            'max_length'  => 0, // max_length 未知
            'base_url'    => 'https://api.openai.com/v1',
            'api_path'    => '/embeddings',
            'vector_type' => 'FLOAT32',
            'vector_dim'  => 3072,
        ],
    ];

    public static string $defaultSessionTitle = '默认会话';

    /**
     * 获取支持的模型和它的名字
     */
    public static function getModelNames(): array
    {
        $content       = [];
        $chatModelAttr = Helper::$chatModelAttr;
        foreach ($chatModelAttr as $key => $item) {
            $content[$key] = $item['name'];
        }
        return $content;
    }

    /**
     * 粗略计算token数
     * @param string $text
     * @return int
     */
    public static function calcTokens(string $text): int
    {
        // 英文单词数
        $enCount = str_word_count($text);

        // 中文汉字数
        $zhCnCount = mb_strlen($text);
        return $enCount + $zhCnCount;
    }

    /**
     * 根据token占比切割字符串
     */
    public static function substrTokens(string $text, int $tokens, string $order = 'asc'): string
    {
        // 字母数量
        preg_match_all("/[a-zA-Z]/", $text, $letter);
        $letterCount = count($letter[0]);

        // 单词数量
        $enCount = str_word_count($text);

        // 截取长度修整
        $tokens = ($tokens - $enCount) + $letterCount;

        return mb_substr($text, $order == 'asc' ? 0 : (-1) * $tokens, $tokens, 'utf8');
    }

    /**
     * 从一个知识点获取需要做向量转换的内容
     * 问答对只转换标题，文档转换标题和内容
     */
    public static function getNeedEmbeddingContent(array $content): string
    {
        $text = $content['title'];
        if ($content['type'] == 'text') {
            $text .= $content['content'];
        }
        return $text;
    }

    /**
     * 获取AI配置
     */
    public static function getConfig(): array
    {
        if (self::$config) return self::$config;

        $data   = Config::select();
        $config = [];
        foreach ($data as $k => $v) {
            $config[$v['name']] = $v['value'];
        }
        self::$config = $config;
        return $config;
    }

    /**
     * 获取会话列表
     * @throws Throwable
     */
    public static function getSessionList(int $aiUserId): array
    {
        $sessionList = Session::where('status', 1)
            ->where('user_id', $aiUserId)
            ->order('create_time', 'desc')
            ->select()
            ->toArray();

        if (!$sessionList) {
            $sessionList[] = self::createSession($aiUserId);
        }
        foreach ($sessionList as $key => $item) {
			if(is_string($item['create_time'])){
				//todo 时间转换 需要验证
		        $item['create_time']= strtotime($item['create_time']);
	        }
            $sessionList[$key]['create_time'] = Date::human($item['last_message_time'] ?? $item['create_time']);
        }
        return $sessionList;
    }

    /**
     * 创建会话
     */
    public static function createSession(int $aiUserId, string $title = ''): array
    {
        $sessionInfo = Session::create([
            'title'   => $title ?: self::$defaultSessionTitle,
            'user_id' => $aiUserId,
        ]);
        return $sessionInfo->toArray();
    }

    /**
     * 获取请求对象
     * @param array $options
     * @param array $headers
     * @param bool  $static
     * @return Client
     */
    public static function getClient(array $options = [], array $headers = [], bool $static = true): Client
    {
        $config         = self::getConfig();
        $defaultOptions = [
            'verify'      => false,
            'http_errors' => false,
            // 默认使用系统配置中的key和代理
            'headers'     => array_merge([
                'Authorization' => 'Bearer ' . $config['ai_api_key'],
                'Content-Type'  => 'application/json',
            ], $headers),
            'proxy'       => $config['ai_proxy']
        ];
        $aiClient       = new Client(array_merge($defaultOptions, $options));
        if ($static && is_null(self::$client)) {
            self::$client = $aiClient;
        }
        return $aiClient;
    }
}