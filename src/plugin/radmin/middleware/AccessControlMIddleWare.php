<?php

namespace plugin\radmin\middleware;

use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class AccessControlMIddleWare implements MiddlewareInterface
{
    public function process(Request $request, callable $handler): Response
    {
        // OPTIONS 请求处理
        if ($request->method() === 'OPTIONS') {
            $response = new Response(200);
            return $this->addCorsHeaders($response, $request);
        }

        // 继续处理请求
        $response = $handler($request);

        // 添加 CORS 头部
        return $this->addCorsHeaders($response, $request);
    }

    protected function addCorsHeaders(Response $response, Request $request): Response
    {
        $header = [
            // 'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Allow-Origin' => $request->header('origin', '*'),
            'Access-Control-Allow-Methods' => $request->header('access-control-request-method', '*'),
            'Access-Control-Allow-Headers' => $request->header('access-control-request-headers', '*'),
        ];

        $origin = $request->header('origin');
        $corsDomain = explode(',',  config('buildadmin.cors_request_domain'));
        $corsDomain[] = $request->host(true);

        if (in_array("*", $corsDomain) || in_array($origin, $corsDomain)) {
            $header['Access-Control-Allow-Origin'] = $origin;
        }

        // SSE 请求处理
        if ($request->header('accept') === 'text/event-stream') {
            $header['Content-Type'] = 'text/event-stream';
        }

        // 给响应添加跨域相关的HTTP头
        $response->withHeaders($header);
		
        return $response;
    }
}
