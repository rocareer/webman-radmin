<?php
/** @noinspection PhpUndefinedFieldInspection */

/** @noinspection PhpPossiblePolymorphicInvocationInspection */

namespace plugin\radmin\middleware;

use Exception;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class RequestMiddleWare implements MiddlewareInterface
{
    /**
     * @throws Exception
     */
    public function process(Request $request, callable $handler): Response
    {
        /**
         * 全局 token 检查
         */
        $request->token();

        /**
         * 全局请求日志
         */
        // if (config('plugin.radmin.app.request.log.enable')) {
        //     //生成全局 requestID
        //     $request->requestID = uniqid('R-', true);
        //     $logContent         = [
        //         'requestID' => $request->requestID,
        //         'url'       => $request->url(),
        //         'method'    => $request->method(),
        //         'IP'        => $request->getRealIp(),
        //         'time'      => time()
        //     ];
        //     Log::channel(config('plugin.radmin.app.request.log.channel'))->info('Request', $logContent);
        // }


        return $handler($request);
    }


}
