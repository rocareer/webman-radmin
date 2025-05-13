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

use exception;
use plugin\radmin\extend\ba\Date;
use Throwable;
use DateInterval;
use plugin\radmin\support\think\Db;
use plugin\radmin\support\Cache;
use Workerman\Protocols\Http\Chunk;
use Psr\Http\Message\StreamInterface;
use Workerman\Protocols\Http\Response;
use plugin\radmin\app\common\model\ai\AiUser;
use think\db\exception\DataNotFoundException;
use plugin\radmin\app\common\model\ai\Session;
use think\db\exception\ModelNotFoundException;
use plugin\radmin\app\common\model\ai\ChatModel;
use plugin\radmin\app\common\model\ai\UserTokens;

use plugin\radmin\app\common\model\ai\SessionMessage;

class Chat
{
    /**
     * AI系统配置
     */
    protected array $config = [];

    /**
     * 模型配置信息
     */
    protected mixed $modelConfig = [];

    /**
     * 会话信息
     */
    protected mixed $sessionInfo = [];

    /**
     * AI会员信息
     */
    protected mixed $aiUserInfo = [];

    /**
     * 输入数据
     */
    protected array $inputs = [];

    /**
     * tokens占比配置
     */
    protected array $tokensPercent = [];

    protected string $aiSessionCachePrefix = 'ai-session-';

    protected bool $debug = false;

    protected string $logFile = '';

    /**
     * 临时数据
     */
    protected array $tempData = [
        'kbs'                   => '',
        'userMessageTokens'     => 0,
        'aiOutputMessageTokens' => 0,
        'aiInputMessageTokens'  => 0,
        'messageTitle'          => '',
    ];
	protected $connection = null;
	
	/**
	 * 构造函数
	 *
	 * @throws Exception
	 */
    public function __construct(protected int $aiUserId, protected bool $stream, array $config)
    {
	    
	    
	    try {
		    $this->connection=request()->connection;
		    if(empty($config)) {
			    $config = Helper::getConfig();
		    }
		    $this->config = array_merge($this->config, $config);
		    if($this->stream) {
			    $headers =  [
				    'X-Accel-Buffering' => 'no',
				    'Content-Type'      => 'text/event-stream',
				    'Cache-Control'     => 'no-cache',
				    "Transfer-Encoding" => "chunked",
				    'Access-Control-Allow-Origin' => '*',
			    ];
			    $this->connection->send(new Response(200, $headers, "\r\n"));
			   
		    }                                     // 获取输入
		    $this->inputs = request()->post();    // 调试日志初始化
		
		    
		    $this->debug  =  config('ai.debug');
		    if($this->debug) {
			                $fileName      = mb_substr(filter_var($this->inputs['message'], FILTER_SANITIZE_FULL_SPECIAL_CHARS), 0, 40);
			                $this->logFile =  config('ai.log_dir') . date('Y-m-d') .
			    	            DIRECTORY_SEPARATOR . $fileName . '.log';
			    			
			                if (!is_dir(dirname($this->logFile))) {
			                    mkdir(dirname($this->logFile), 0755, true);
			                }
		    }// 获取模型配置
		    $this->modelConfig = ChatModel::where('name', $this->inputs['ai_model'])
		                                  ->where('status', 1)
		                                  ->order('weigh desc')
		                                  ->find()
		    ;
		    if(!$this->modelConfig) {
			    return $this->error('模型找不到啦！');
		    }// 获取会话信息
		    $this->sessionInfo               = Session::where('id', $this->inputs['id'])
		                                              ->where('user_id', $this->aiUserId)
		                                              ->where('status', 1)
		                                              ->find()
		    ;// 获取AI会员信息
		    $this->aiUserInfo                = AiUser::where('id', $this->aiUserId)
		                                             ->where('status', 1)
		                                             ->find()
		    ;
		    $this->aiUserInfo->last_use_time = time();
		    $this->aiUserInfo->save();
		    if($this->aiUserInfo->tokens <= 0) {
			    return $this->error('可用tokens不足！');
		    }// 消息标题
		    if(isset($this->inputs['message'])) {
			    $this->tempData['messageTitle'] = mb_substr($this->inputs['message'], 0, 20).(mb_strlen($this->inputs['message']) > 20 ? '...' : '');
		    }
		    $this->checkStopCache();
	    }
	    catch(\Exception $e) {
			return $this->error($e->getMessage());
	    }
    }

    /**
     * 检查是否有中断缓存
     */
    public function checkStopCache()
    {
        $cache = Cache::store('ai')->get($this->aiSessionCachePrefix . $this->inputs['id']);
        if ($cache) {
            $lastMessageType = SessionMessage::where('session_id', $this->inputs['id'])
                ->order('create_time', 'desc')
                ->value('sender_type');
            if ($lastMessageType == 'user') {
                $this->tempData['kbs']                   = $cache['kbs'];
                $this->tempData['messageTitle']          = $cache['messageTitle'];
                $this->tempData['userMessageTokens']     = $cache['userMessageTokens'];
                $this->tempData['aiInputMessageTokens']  = $cache['aiInputMessageTokens'];
                $this->tempData['aiOutputMessageTokens'] = $cache['aiOutputMessageTokens'];
                $this->recordMessage('ai', $cache['aiContent'], $cache['aiReasoningContent'], false);
            }
        }
    }

    /**
     * 组装发送给AI的 messages 数组
     */
    public function getMessages(): array
    {
        // 根据tokens的占比配置计算token数
        $assignableSum   = Helper::$chatModelAttr[$this->inputs['ai_model']]['sum'] - Helper::$chatModelAttr[$this->inputs['ai_model']]['reserve'];
        $percentInputKey = ['ai_short_memory_percent', 'ai_kbs_percent', 'ai_user_input_percent'];
        foreach ($percentInputKey as $inputKey) {
            $percent                        = $this->config['ai_allow_users_edit_tokens'] ? $this->inputs[$inputKey] : $this->config[$inputKey];
            $this->tokensPercent[$inputKey] = intval(($percent / 100) * $assignableSum);
        }

        // 发送给AI的消息数组
        $messages = [];

        // 系统提示词
        $systemPrompt = $this->config['ai_allow_users_edit_prompt'] ? $this->inputs['ai_system_prompt'] : $this->config['ai_system_prompt'];
        if ($systemPrompt && !in_array('ai_system_prompt', Helper::$chatModelAttr[$this->modelConfig['name']]['unsupported_parameters'])) {
            $messages[] = [
                'role'    => 'system',
                'content' => $systemPrompt,
            ];

            $this->tempData['aiInputMessageTokens'] += Helper::calcTokens($systemPrompt);
        }

        // 历史消息载入 - 倒序的检查是否成整轮，是否达到tokens限制，然后顺序载入
        $history      = SessionMessage::where('session_id', $this->inputs['id'])
            ->order('create_time desc,id desc')
            ->limit($this->inputs['ai_short_memory'] * 2)
            ->select();
        $turn         = 0;
        $validHistory = [];
        $nextUserType = 'ai';
        foreach ($history as $item) {
            if ($item['sender_type'] == $nextUserType && $item['content']) {
                $validHistory[$turn][$nextUserType] = $item;

                if ($nextUserType == 'user') {
                    $turn++;
                    $nextUserType = 'ai';
                } else {
                    $nextUserType = 'user';
                }
            } else {
                // 对话连续性中断，数据不合规，取消载入
                break;
            }
        }

        // 历史记录 token 占比检查
        $historyTokens = 0;
        // 可以载入的历史记录，准备好之后需要反转
        $historyMessages = [];
        foreach ($validHistory as $item) {
            if (isset($item['ai']) && isset($item['user'])) {
                $historyTokens += Helper::calcTokens($item['ai']['content'] . $item['user']['content']);
                if ($historyTokens <= $this->tokensPercent['ai_short_memory_percent']) {
                    $historyMessages[] = [
                        'role'    => 'assistant',
                        'content' => $item['ai']['content'],
                    ];
                    $historyMessages[] = [
                        'role'    => 'user',
                        'content' => $item['user']['content'],
                    ];
                } else {
                    break;
                }
            }
        }
        $messages                               = array_merge($messages, array_reverse($historyMessages));
        $this->tempData['aiInputMessageTokens'] += $historyTokens;

        // 用户输入 token 占比检查
        $userMessageTokens = Helper::calcTokens($this->inputs['message']);
        if ($userMessageTokens > $this->tokensPercent['ai_user_input_percent']) {
            $this->inputs['message'] = Helper::substrTokens($this->inputs['message'], $this->tokensPercent['ai_user_input_percent']);
        }

        // 身份提示词
        $prompt = $this->config['ai_allow_users_edit_prompt'] ? $this->inputs['ai_prompt'] : $this->config['ai_prompt'];

        // 知识库关闭-s
        if (!$this->inputs['ai_kbs_open'] && $this->config['ai_allow_users_close_kbs']) {

            if ($prompt && str_contains($prompt, '${question}')) {
                $prompt = str_replace('${question}', $this->inputs['message'], $prompt);
            } elseif ($prompt) {
                $prompt = $prompt . $this->inputs['message'];
            } else {
                $prompt = $this->inputs['message'];
            }

            $messages[] = [
                'role'    => 'user',
                'content' => $prompt,
            ];

            $this->tempData['aiInputMessageTokens'] += Helper::calcTokens($prompt);

            return $messages;
        }
        // 知识库关闭-e

        if ($prompt) {
            $reference               = '';
            $irrelevantDetermination = true;// 是否无关
            try {
                $where = [];
                if ($this->config['ai_allow_users_select_kbs'] && $this->config['ai_allow_users_close_kbs']) {
                    if ($this->inputs['ai_kbs']) $where['kbs'] = array_filter($this->inputs['ai_kbs']);
                    if ($this->inputs['ai_kbs_type']) $where['type'] = $this->inputs['ai_kbs_type'];
                }
                if (Helper::$embeddingModelAttr[$this->inputs['ai_api_type']]['text_type']) {
                    $where['text_type'] = $this->inputs['ai_kbs_text_type'] ?? 'query';
                }

                $embeddings = Embeddings::instance();
                $match      = $embeddings->getTextMatchData($this->inputs['message'], $this->inputs['ai_effective_kbs_count'], $this->inputs['ai_kbs_text_type'], $where);

                // 记录用户消息token数
                $this->tempData['userMessageTokens'] = $match['tokens'];

                // 禁止修改有效知识配置 - 恢复默认值
                if (!$this->config['ai_allow_users_edit_effective_kbs']) {
                    $this->inputs['ai_effective_match_kbs']      = $this->config['ai_effective_match_kbs'];
                    $this->inputs['ai_irrelevant_determination'] = $this->config['ai_irrelevant_determination'];
                }

                foreach ($match['data'] as $item) {
                    if ($item['similarity'] > $this->inputs['ai_irrelevant_determination']) {
                        $irrelevantDetermination = false;
                    }

                    // 有效知识
                    if ($item['similarity'] >= $this->inputs['ai_effective_match_kbs']) {
                        $reference             .= $item['data']['content'] . "\n";
                        $this->tempData['kbs'] .= $item['data']['id'] . ',';
                    }
                }

                $this->tempData['kbs'] = rtrim($this->tempData['kbs'], ',');
            } catch (Throwable $e) {
                return $this->error($e->getMessage() . ' - ' . $e->getFile() . ' - ' . $e->getLine());
            }

            // 无关中断回复
            if ($irrelevantDetermination && $this->inputs['ai_irrelevant_message'] && $this->config['ai_allow_users_edit_effective_kbs']) {
                return $this->error($this->inputs['ai_irrelevant_message']);
            }

            // 知识库内容 token 占比
            $kbsTokens = Helper::calcTokens($reference);
            if ($kbsTokens > $this->tokensPercent['ai_kbs_percent']) {
                $reference = Helper::substrTokens($reference, $this->tokensPercent['ai_kbs_percent']);
            }

            // 如果知识点为空，使用另外的提示词
            if ($reference) {
                $prompt = str_replace('${reference}', $reference, $prompt);
            } else {
                $prompt = $this->config['ai_prompt_no_reference'];
            }

            if (str_contains($prompt, '${question}')) {
                $prompt = str_replace('${question}', $this->inputs['message'], $prompt);
            } else {
                $prompt = $prompt . $this->inputs['message'];
            }

            $messages[] = [
                'role'    => 'user',
                'content' => $prompt,
            ];

            $this->tempData['aiInputMessageTokens'] += Helper::calcTokens($prompt);
        } else {
            $messages[] = [
                'role'    => 'user',
                'content' => $this->inputs['message'],
            ];

            $this->tempData['aiInputMessageTokens'] += Helper::calcTokens($this->inputs['message']);
        }
        return $messages;
    }

    /**
     * 组装发送给AI的额外参数
     */
    public function getParameters(): array
    {
        $params = [];

        // 温度
        if (!in_array('temperature', Helper::$chatModelAttr[$this->modelConfig['name']]['unsupported_parameters'])) {
            $params['temperature'] = (float)$this->inputs['temperature'];
        }
        // 核采样
        if (!in_array('top_p', Helper::$chatModelAttr[$this->modelConfig['name']]['unsupported_parameters'])) {
            $params['top_p'] = (float)$this->inputs['top_p'];
        }
		
        // 核采样候选集大小
        if (!in_array('top_k', Helper::$chatModelAttr[$this->modelConfig['name']]['unsupported_parameters'])) {
            $params['top_k'] = (float)$this->inputs['top_k'];
        }
		
        // 参考夸克搜索结果
        if ($this->inputs['ai_enable_search'] && !in_array('ai_enable_search', Helper::$chatModelAttr[$this->modelConfig['name']]['unsupported_parameters'])) {
            $params['enable_search'] = true;
        }
		
        // 话题新鲜度
        if (!in_array('ai_presence_penalty', Helper::$chatModelAttr[$this->modelConfig['name']]['unsupported_parameters'])) {
            $params['presence_penalty'] = (float)$this->inputs['ai_presence_penalty'];
        }
		
        // 字词频率惩罚
        if (!in_array('ai_frequency_penalty', Helper::$chatModelAttr[$this->modelConfig['name']]['unsupported_parameters'])) {
            $params['frequency_penalty'] = (float)$this->inputs['ai_frequency_penalty'];
        }

        // 回复内容最大tokens数
        if ($this->inputs['ai_max_tokens']) {
            $params['max_tokens'] = (float)$this->inputs['ai_max_tokens'];
        }

        if (Helper::$chatModelAttr[$this->modelConfig['name']]['api_type'] == 'ali') {
            $params['result_format'] = 'message';
        } else {
            $params['stream'] = true;
            $params['user']   = (string)$this->aiUserId;
        }
		
        return $params;
    }

    /**
     * 生成回答
     * 不支持非stream！
     */
	public function generation()
	{
		set_time_limit(0);
		
		$clientOptions = [];
		if ($this->modelConfig['proxy']) {
			$clientOptions['proxy'] = $this->modelConfig['proxy'];
		}
		
		$client = Helper::getClient($clientOptions, [
			'Authorization'   => 'Bearer ' . $this->modelConfig['api_key'],
			'Content-Type'    => 'text/event-stream',
			'X-DashScope-SSE' => 'enable',
		], false);
		
		try {
			// URL
			$url = $this->modelConfig['api_url'] ?: Helper::$chatModelAttr[$this->modelConfig['name']]['api_url'];
			
			$postJson = [
				'model' => $this->modelConfig['name'],
			];
			
			if (Helper::$chatModelAttr[$this->modelConfig['name']]['api_type'] == 'ali') {
				$postJson['input']['messages'] = $this->getMessages();
				$postJson['parameters']        = $this->getParameters();
			} else {
				$postJson['messages'] = $this->getMessages();
				$postJson             = array_merge($postJson, $this->getParameters());
			}
			
			if ($this->debug) {
				@file_put_contents($this->logFile, var_export($postJson, true));
			}
			
			$response = $client->post($url, [
				'json'         => $postJson,
				'stream'       => true,
				'read_timeout' => 60,
			]);
			$body     = $response->getBody();
			
			// 异常检查
			$contentType = $response->getHeader('Content-Type');
			if (isset($contentType[0]) && $contentType[0] == 'application/json') {
				$error = json_decode($body->getContents(), true);
				if ($error) {
					if (isset($error['error']['message'])) {
						$this->error($error['error']['message']);
					}
					$this->error(var_export($error, true));
				}
			}
			
			// 用户消息记录
			$this->recordMessage('user', $this->inputs['message']);
			
			if (Helper::$chatModelAttr[$this->modelConfig['name']]['api_type'] == 'ali') {
				// 阿里千问输出读取
				$this->aliQwenOutputRead($body);
			} else {
				// ChatGPT输出读取
				$this->chatGptOutputRead($body);
			}
		} catch (AiInterrupter $e) {
			throw new AiInterrupter($e->getMessage());
		} catch (Throwable $e) {
			$this->error($e->getMessage() . ' - ' . $e->getFile() . ' - ' . $e->getLine());
		}
	}
	
	/**
	 * 千问输出读取
	 * 直接向前端发送原始输出数据
	 */
	public function aliQwenOutputRead(StreamInterface $body)
	{
		$buffer = '';
		while (!$body->eof()) {
			$buffer .= $body->read(1024);
			
			if ($this->debug) {
				@file_put_contents($this->logFile, "\nbuffer-read:$buffer\n", FILE_APPEND);
			}
			
			// 异常检测
			if (str_contains($buffer, '{"code":"')) {
				$error = json_decode($buffer, true);
				if ($error && isset($error['message'])) {
					$this->error($error['message']);
				}
			}
			
			if (str_starts_with($buffer, '<html>')) {
				$this->error($buffer);
			}
			
			// 找到单次回复的结尾标记 - 一次读取中可能会有多个完整的SSE数据，此处进行切割，分别发送给前端
			$buffers = explode("\n\n", $buffer);
			
			if ($this->debug) {
				@file_put_contents($this->logFile, "\nbuffers:" . var_export($buffers, true) . "\n----------------------\n", FILE_APPEND);
			}
			
			// 总是将最后一个数组留给下一轮（末尾刚好是两个换行的，留下的为空，不是两个换行的则数据不完整）
			$buffer = array_pop($buffers);
			
			foreach ($buffers as $item) {
				// 分行
				$bufferRow = explode("\n", $item);
				
				foreach ($bufferRow as $row) {
					// 确定为 data 行
					if (str_starts_with($row, 'data:{')) {
						// 确定 data 完整性
						$rowJson = str_replace('data:{', '{', $row);
						$rowArr  = json_decode($rowJson, true);
						if (json_last_error() == JSON_ERROR_NONE) {
							if (isset($rowArr['output']['choices'][0]['finish_reason'])) {
								if ($rowArr['output']['choices'][0]['finish_reason'] != 'null') {
									// AI生成结束
									$this->tempData['aiOutputMessageTokens'] = $rowArr['usage']['output_tokens'];
									$this->tempData['aiInputMessageTokens']  = $rowArr['usage']['input_tokens'];
									$this->recordMessage('ai', $rowArr['output']['choices'][0]['message']['content']);
									Cache::store('ai')->delete($this->aiSessionCachePrefix .
										$this->inputs['id']);
								} else {
									// AI生成中-缓存数据以免消息丢失
									$cacheData = [
										'kbs'                   => $this->tempData['kbs'],
										'messageTitle'          => $this->tempData['messageTitle'],
										'userMessageTokens'     => $this->tempData['userMessageTokens'],
										'aiInputMessageTokens'  => $rowArr['usage']['input_tokens'],
										'aiOutputMessageTokens' => $rowArr['usage']['output_tokens'],
										'aiContent'             => $rowArr['output']['choices'][0]['message']['content'],
										'aiReasoningContent'    => '',
									];
									Cache::store('ai')->set($this->aiSessionCachePrefix .
										$this->inputs['id'], $cacheData, DateInterval::createFromDateString('1 day'));
								}
							}
							$this->output($rowJson);
						}
					}
				}
			}
		}
	}
	
	/**
	 * ChatGPT输出读取
	 * 格式化后向前端发送 code=2,content=内容,finish_reason:stop=生成完毕,null=生成中
	 */
	public function chatGptOutputRead(StreamInterface $body)
	{
		$buffer                   = '';
		$SSEof                    = "\n\n";
		$aiOutputMessage          = '';
		$aiOutputReasoningMessage = '';
		while (!$body->eof()) {
			$buffer .= $body->read(512);
			
			if ($this->debug) {
				@file_put_contents($this->logFile, "\nbuffer-read:$buffer\n", FILE_APPEND);
			}
			
			// 异常检查
			if (str_contains($buffer, '"error":')) {
				$error = json_decode($buffer, true);
				if ($error && isset($error['error']['message'])) {
					$this->error($error['error']['message']);
				}
			}
			if (str_starts_with($buffer, '<html>')) {
				$this->error($buffer);
			}
			
			// 找到单次回复的结尾标记 - 一次读取中可能会有多个完整的SSE数据，此处进行切割，分别发送给前端
			$buffers = explode($SSEof, $buffer);
			
			if ($this->debug) {
				@file_put_contents($this->logFile, "\nbuffers:" . var_export($buffers, true) . "\n----------------------\n", FILE_APPEND);
			}
			
			// 总是将最后一个数组留给下一轮（末尾刚好是两个换行的，留下的为空，不是两个换行的则数据不完整）
			$buffer = array_pop($buffers);
			
			foreach ($buffers as $row) {
				// AI生成完毕
				if ($row == 'data: [DONE]') {
					// 消息记录入库
					$this->tempData['aiOutputMessageTokens'] = Helper::calcTokens($aiOutputReasoningMessage . $aiOutputMessage);
					$this->recordMessage('ai', $aiOutputMessage, $aiOutputReasoningMessage);
					Cache::store('ai')->delete($this->aiSessionCachePrefix . $this->inputs['id']);
					
					$this->output([
						'code'              => 2,
						'finish_reason'     => 'stop',
						'content'           => $aiOutputMessage,
						'reasoning_content' => $aiOutputReasoningMessage,
					]);
					break;
				}
				
				// AI生成中 - 确定 data 完整性
				$rowJson = str_replace('data: {', '{', $row);
				$rowArr  = json_decode($rowJson, true);
				if (json_last_error() == JSON_ERROR_NONE) {
					$aiOutputMessage          .= $rowArr['choices'][0]['delta']['content'] ?? '';
					$aiOutputReasoningMessage .= $rowArr['choices'][0]['delta']['reasoning_content'] ?? '';
					$this->output([
						'code'              => 2,
						'finish_reason'     => 'null',
						'content'           => $aiOutputMessage,
						'reasoning_content' => $aiOutputReasoningMessage,
					]);
					
					// 缓存输出 tokens 数等，以免停止生成时数据丢失
					$cacheData = [
						'kbs'                   => $this->tempData['kbs'],
						'messageTitle'          => $this->tempData['messageTitle'],
						'userMessageTokens'     => $this->tempData['userMessageTokens'],
						'aiInputMessageTokens'  => $this->tempData['aiInputMessageTokens'],
						'aiOutputMessageTokens' => Helper::calcTokens($aiOutputReasoningMessage . $aiOutputMessage),
						'aiContent'             => $aiOutputMessage,
						'aiReasoningContent'    => $aiOutputReasoningMessage,
					];
					Cache::store('ai')->set($this->aiSessionCachePrefix . $this->inputs['id'],
						$cacheData, DateInterval::createFromDateString('1 day'));
				}
			}
		}
	}

    /**
     * 消息记录入库
     */
    public function recordMessage($userType, $content, $reasoningContent = '', $outputMessageInfo = true): bool
    {
        $amount = 0;
        if ($userType == 'ai') {
            // 用户消息向量转换tokens + AI输入tokens + AI输出tokens
            $tokens = $this->tempData['userMessageTokens'] + $this->tempData['aiInputMessageTokens'] + $this->tempData['aiOutputMessageTokens'];
            $amount = $tokens * $this->modelConfig['tokens_multiplier'];

            // 扣取
            if ($amount) {
                Db::startTrans();
                try {
                    UserTokens::create([
                        'ai_user_id' => $this->aiUserId,
                        'tokens'     => intval($amount) * (-1),
                        'memo'       => 'AI知识库：' . $this->tempData['messageTitle'],
                    ]);
                    Db::commit();
                } catch (Throwable $e) {
                    Db::rollback();
                    throw $e;
                }
            }

        }

        $sessionMessage = SessionMessage::create([
            'type'               => 'text',
            'session_id'         => $this->inputs['id'],
            'sender_type'        => $userType,
            'sender_id'          => $userType == 'user' ? $this->aiUserInfo->nickname : $this->inputs['ai_model'],
            'reasoning_content'  => $reasoningContent,
            'content'            => $content,
            'tokens'             => $userType == 'user' ? $this->tempData['userMessageTokens'] : $this->tempData['aiInputMessageTokens'] + $this->tempData['aiOutputMessageTokens'],
            'kbs'                => $userType == 'user' ? '' : $this->tempData['kbs'],
            'consumption_tokens' => $amount,
        ]);

        // 修改会话标题
        if ($this->sessionInfo->title == Helper::$defaultSessionTitle) {
            $this->sessionInfo->title = $this->tempData['messageTitle'];
            $this->sessionInfo->save();
            $this->output([
                'code'  => 1,
                'type'  => 'newTitle',
                'value' => $this->tempData['messageTitle'],
            ]);
        }

        // 发送消息资料至前台
        if ($outputMessageInfo) {
            if ($this->aiUserInfo->user_type == 'user') {
                unset($sessionMessage['kbs']);
            }

            $sessionMessage['last_message_time'] = Date::human(time() - 1);

            $this->output([
                'code'  => 1,
                'type'  => 'messageInfo',
                'value' => $sessionMessage,
                'uuid'  => $this->inputs['uuid'],
            ]);
        }

        return true;
    }

    /**
     * 输出
     */
    public function output(string|array $data)
    {
        if (is_array($data)) $data = json_encode($data, JSON_UNESCAPED_UNICODE);
	    if (headers_sent($file, $line)) {
		    error_log("Headers already sent in $file on line $line");
	    }
		
//		$this->connection->send( 'data: ' . $data . "\n\n");
	    $this->connection->send(new Chunk('data: ' . $data . "\n\n"));
        if ($this->stream) {
            // 刷新浏览器缓冲区
//            @ob_flush();
        }
    }

    /**
     * 错误
     * @throws AiInterrupter
     */
    public function error(string $msg)
    {
        $this->output(['code' => 0, 'msg' => $msg]);
        throw new AiInterrupter($msg);
    }
}