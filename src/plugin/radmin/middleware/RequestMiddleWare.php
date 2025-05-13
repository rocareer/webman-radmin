<?php
/** @noinspection PhpUndefinedFieldInspection */

/** @noinspection PhpPossiblePolymorphicInvocationInspection */

namespace plugin\radmin\middleware;

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
        if (config('app.request.log.enable')) {
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

        $controller = explode('\\', $request->controller);
        $controller = array_slice($controller, -2);

        $request->controllerName = strtolower(implode('/', $controller));




        $this->fixedInitValue($request);

        return $handler($request);
    }

    /**
     * 适配前端组装 initValue 数据
     * @param $request
     * @return   void
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/12 08:21
     */
    public function fixedInitValue($request): void
    {
        if ($request->input('initValue') !== null) {
            $initValue = $request->input('initValue');
            if ($initValue==''){
                $initValue=[];
            }elseif(!is_array($initValue)) {
                $initValue = explode(',', $initValue);
            }
            $request->setGet('initValue', $initValue);
        }
    }

}
