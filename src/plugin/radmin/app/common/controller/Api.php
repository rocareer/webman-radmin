<?php

namespace plugin\radmin\app\common\controller;

use plugin\radmin\app\controller\BaseController;
use Exception;
use plugin\radmin\exception\ServerErrorHttpException;
use plugin\radmin\support\think\Lang;
use plugin\radmin\support\Response;
use plugin\radmin\support\think\orm\Rdb;
use Throwable;

/**
 * API控制器基类
 */
class Api extends BaseController
{
    /**
     * 默认响应输出类型,支持json/xml/jsonp
     * @var string
     */
    protected string $responseType = 'json';

    /**
     * 应用站点系统设置
     * @var bool
     */
    protected bool $useSystemSettings = true;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 控制器初始化方法
     * @access protected
     * @throws Throwable
     */
    protected function initialize(): void
    {
        // 系统站点配置
        if ($this->useSystemSettings) {
            // 检查数据库连接
            try {
                Rdb::execute("SELECT 1");
            } catch (Exception) {
                throw new ServerErrorHttpException('数据库连接失败');
            }

            ip_check(); // ip检查
            set_timezone(); // 时区设定
        }

        parent::initialize();

        // 加载控制器语言包
        $this->loadControllerLang();
    }


    /**
     * 加载控制器语言包
     * @变更说明：修改为Webman兼容的语言包加载方式
     */
    protected function loadControllerLang(): void
    {
        $langSet        = Lang::getLangSet();
        $controllerPath = str_replace('\\', '/', static::class);
        $controllerPath=explode('controller/',$controllerPath);
        $langPath=$controllerPath[0]."lang";
        $controllerName=$controllerPath[1];
        $langFile       = $langPath.'/'.$langSet.'/'.$controllerName.'.php';

        if(is_file($langFile)) {
            Lang::load($langFile);
        }
        else {
            // 默认加载中文公共语言文件
            $commonLangFile = app_path('admin/lang/').$langSet.'.php';
            if(is_file($commonLangFile)) {
                Lang::load($commonLangFile);
            }
        }
    }

    /**
     * 操作成功
     * @param string      $msg    提示消息
     * @param mixed       $data   返回数据
     * @param int         $code   错误码
     * @param string|null $type   输出类型
     * @param array       $header 发送的 header 信息
     * @return Response
     */
    protected function success(string $msg = '', mixed $data = null, int $code = 1, ?string $type = null, array $header = []): ?Response
    {
       return $this->result($msg, $data, $code, $type, $header);
    }

    /**
     * 操作失败
     * @param string      $msg    提示消息
     * @param mixed       $data   返回数据
     * @param int         $code   错误码
     * @param string|null $type   输出类型
     * @param array       $header 发送的 header 信息
     * @return Response|null
     */
    protected function error(string $msg = '', mixed $data = null, int $code = 0, ?string $type = null, array $header = []):?Response
    {
      return  $this->result($msg, $data, $code, $type, $header);
    }

    /**
     * 返回 API 数据
     * @param string      $msg  提示消息
     * @param mixed       $data 返回数据
     * @param int         $code 错误码
     * @param string|null $contentType
     * @param array       $headers
     * @return Response|null
     */
    public function result(string $msg, mixed $data = null, int $code = 0, ?string $contentType = null, array $headers = []):?Response
    {
        // $start = microtime(true);

        $responseData = [
            'code' => $code,
            'msg'  => $msg,
            'time' => time(),
            'data' => $data,
        ];

        $json     = json_encode($responseData, JSON_UNESCAPED_UNICODE);
        $response = new Response($headers['statusCode'] ?? 200, array_diff_key($headers, ['statusCode' => null]), $json);

        // 使用更高效的头信息设置方式
        $response->withHeaders([
            'Content-Type' => $contentType ?: $this->responseType ?? 'application/json',
        ]);



        //		 性能日志记录阈值可配置化
        //		$elapsed = microtime(true) - $start;
        //		if($elapsed > config('plugin.radmin.slow_request_threshold', 0.7)) {
        //			Log::debug('Slow response: '.round($elapsed, 3).'s', [
        //				'uri'  => $this->request->uri(),
        //				'data' => $data,
        //			]);
        //		}

        return $response->withHeaders($headers);
    }

}
