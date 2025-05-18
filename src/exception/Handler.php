<?php
/**
 * @desc ExceptionHandler
 * @author Tinywan(ShaoBo Wan)
 * @email 756684177@qq.com
 * @date 2022/3/6 14:08
 */
declare(strict_types=1);

namespace Radmin\exception;

use FastRoute\BadRouteException;
use Radmin\Log;
use Radmin\Response;
use think\db\exception\DbException;
use think\exception\ValidateException;
use Throwable;
use Webman\Exception\ExceptionHandler;
use Webman\Http\Request;

class Handler extends ExceptionHandler
{
    /**
     * 不需要记录错误日志.
     *
     * @var string[]
     */
    public $dontReport = [];

    /**
     * HTTP Response Status Code.
     *
     * @var array
     */
    public int $statusCode = 200;

    /**
     * HTTP Response Header.
     *
     * @var array
     */
    public array $header = [];

    /**
     * Business Error code.
     *
     * @var int
     */
    public int $errorCode = 0;

    /**
     * Business Error message.
     *
     * @var string
     */
    public string $errorMessage = 'no error';

    /**
     * 响应结果数据.
     *
     * @var array
     */
    protected array $responseData = [];

    /**
     * config下的配置.
     *
     * @var array
     */
    protected array $config = [];

    /**
     * Log Error message.
     *
     * @var string
     */
    public string $error = 'no error';

    /**
     * @param Throwable $exception
     */
    public function report(Throwable $exception)
    {
        $this->dontReport =  config('plugin.radmin.exception.exception_handler.dont_report', []);
        parent::report($exception);
    }

    /**
     * @param Request $request
     * @param Throwable $exception
     * @return Response
     */
    public function render(Request $request, Throwable $exception): Response
    {
        $this->config = array_merge($this->config,  config('plugin.radmin.exception.exception_handler', []) ?? []);

        $this->addRequestInfoToResponse($request);
        $this->solveAllException($exception);
        $this->addDebugInfoToResponse($exception);
        // $this->triggerNotifyEvent($exception);
        // $this->triggerTraceEvent($exception);

        return $this->buildResponse();
    }

    /**
     * 请求的相关信息.
     *
     * @param Request $request
     * @return void
     */
    protected function addRequestInfoToResponse(Request $request): void
    {
        $this->responseData = array_merge($this->responseData, [
            'domain' => $request->host(),
            'method' => $request->method(),
            'request_url' => $request->method() . ' ' . $request->uri(),
            'timestamp' => date('Y-m-d H:i:s'),
            'client_ip' => $request->getRealIp(),
            'request_param' => $request->all(),
        ]);
    }

    /**
     * 处理异常数据.
     *
     * @param Throwable $e
     */
    protected function solveAllException(Throwable $e): void
    {
        if ($e instanceof Exception) {
            $this->statusCode = $e->statusCode;
            $this->header = $e->header;
            $this->errorCode = $e->errorCode;
            $this->errorMessage = $e->errorMessage;
            $this->error = $e->error;
            if (isset($e->data)) {
                $this->responseData = array_merge($this->responseData, $e->data);
            }
            if (!$e instanceof ServerErrorHttpException) {
                return;
            }
        }
        $this->solveExtraException($e);
    }

    /**
     * @desc: 处理扩展的异常
     * @param Throwable $e
     * @author Tinywan(ShaoBo Wan)
     */
    protected function solveExtraException(Throwable $e): void
    {
        $status = $this->config['status'];
        $this->errorMessage = $e->getMessage();
        if ($e instanceof BadRouteException) {
            $this->statusCode = $status['route'] ?? 404;
        } elseif ($e instanceof \TypeError) {
            $this->statusCode = $status['type_error'] ?? 400;
            $this->errorMessage = isset($status['type_error_is_response']) && $status['type_error_is_response'] ? $e->getMessage() : '网络连接似乎有点不稳定。请检查您的网络！';
            $this->error = $e->getMessage();
        } elseif ($e instanceof ValidateException) {
            $this->statusCode = $status['validate'];
        } elseif ($e instanceof \InvalidArgumentException) {
            $this->statusCode = $status['invalid_argument'] ?? 415;
            $this->errorMessage = '预期参数配置异常：' . $e->getMessage();
        } elseif ($e instanceof DbException) {
            $this->statusCode = 500;
            $this->errorMessage = 'Db：' . $e->getMessage();
            $this->error = $e->getMessage();
        } elseif ($e instanceof ServerErrorHttpException) {
            $this->errorMessage = $e->errorMessage;
            $this->statusCode = 500;
        } else {
            $this->statusCode = $status['server_error'] ?? 500;
            $this->errorMessage = isset($status['server_error_is_response']) && $status['server_error_is_response'] ? $e->getMessage() : 'Internal Server Error';
            $this->error = $e->getMessage();
            Log::error($this->errorMessage, array_merge($this->responseData, [
                'error' => $this->error,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]));
        }
    }

    /**
     * 调试模式：错误处理器会显示异常以及详细的函数调用栈和源代码行数来帮助调试，将返回详细的异常信息。
     * @param Throwable $e
     * @return void
     */
    protected function addDebugInfoToResponse(Throwable $e): void
    {
        if (config('plugin.radmin.app.debug', false)) {
            $this->responseData['error_message'] = $this->errorMessage;
            $this->responseData['error_trace'] = explode("\n", $e->getTraceAsString());
            $this->responseData['file'] = $e->getFile();
            $this->responseData['line'] = $e->getLine();
        }
    }




    /**
     * 构造 Response.
     *
     * @return Response
     */
    protected function buildResponse(): Response
    {
        $bodyKey = array_keys($this->config['body']);
        $bodyValue = array_values($this->config['body']);
        $responseBody = [
                $bodyKey[0] ?? 'code' => $this->setCode($bodyValue, $this->errorCode), // 自定义异常code码
                $bodyKey[1] ?? 'msg' => $this->errorMessage,
                $bodyKey[2] ?? 'data' => $this->responseData,
        ];

        $header = array_merge(['Content-Type' => 'application/json;charset=utf-8'], $this->header);
        return new Response($this->statusCode, $header, json_encode($responseBody));
    }

    private function setCode($bodyValue, $errorCode)
    {
        if($errorCode > 0){
            return $errorCode;
        }
        return  $bodyValue[0] ?? 0;
    }
}
