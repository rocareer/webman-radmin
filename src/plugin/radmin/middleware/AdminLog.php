<?php


namespace plugin\radmin\middleware;

use plugin\radmin\app\admin\model\AdminLog as AdminLogModel;
use Throwable;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class AdminLog implements MiddlewareInterface
{
    /**
     * 写入管理日志
     * @throws Throwable
     */
	public function process(Request $request, callable $handler): Response
    {
        $response = $handler($request);
        if (($request->isPost() || $request->method()=='DELETE'||$request->action=='sync') &&  config('buildadmin.auto_write_admin_log')) AdminLogModel::instance()->record();
        return $response;
    }
}