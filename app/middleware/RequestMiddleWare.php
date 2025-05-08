<?php

namespace middleware;

use Rocareer\Radmin\Exception\TokenException;
use Rocareer\Radmin\Exception\TokenExpirationException;
use support\Log;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class RequestMiddleWare implements MiddlewareInterface
{
    public function process(Request $request, callable $handler): Response
    {

        /**
         * 全局 token 检查
         */

        $request->token = getTokenFromRequest($request);

        /**
         * 全局请求日志
         */
        if (radmin_config('app.request.log.enable')) {
            //生成全局 requestID
            $request->requestID = uniqid('R-', true);
            $logContent         = [
                'requestID' => $request->requestID,
                'url'       => $request->url(),
                'method'    => $request->method(),
                'IP'        => $request->getRealIp(),
                'time'      => time()
            ];
            Log::channel(radmin_config('app.request.log.channel'))->info('Request', $logContent);
        }

        return $handler($request);
    }

}
