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

namespace plugin\radmin\middleware;

use Closure;
use Throwable;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;
use app\admin\model\AdminLog as AdminLogModel;

class AdminLog implements MiddlewareInterface
{
    /**
     * 写入管理日志
     * @throws Throwable
     */
	public function process(Request $request, callable $handler): Response
    {
        $response = $handler($request);
        if (($request->isPost() || $request->method()=='DELETE') && radmin_config('buildadmin.auto_write_admin_log')) {
            try {
                AdminLogModel::instance()->record();
            } catch (Throwable $e) {
                throw $e;
            }
        }
        return $response;
    }
}