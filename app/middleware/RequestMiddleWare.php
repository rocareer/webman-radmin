<?php
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpPossiblePolymorphicInvocationInspection */

namespace app\middleware;

use Exception;
use support\Log;
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

        $request->token = getTokenFromRequest($request);

        /**
         * 全局请求日志
         */
        if ( config('app.request.log.enable')) {
            //生成全局 requestID
            $request->requestID = uniqid('R-', true);
            $logContent         = [
                'requestID' => $request->requestID,
                'url'       => $request->url(),
                'method'    => $request->method(),
                'IP'        => $request->getRealIp(),
                'time'      => time()
            ];
            Log::channel(config('app.request.log.channel'))->info('Request', $logContent);
        }

        $controller              = explode('\\', $request->controller);
        $controller              = array_slice($controller, -2);

        $request->controllerName = strtolower(implode('/', $controller));


        if ($request->get('initValue') !== null) {
            $initValue = $request->get('initValue');
            if (!is_array($initValue)){
                $initValue = explode(',', $initValue);
                $request->setGet('initValue', $initValue);
            }
        }


        return $handler($request);
    }

}
