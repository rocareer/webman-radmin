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

use app\middleware\AccessControlMIddleWare;
use app\middleware\AdminLog;
use app\middleware\AdminRequest;
use app\middleware\AdminSecurity;
use app\middleware\RadminAuthMiddleware;
use app\middleware\RequestMiddleWare;

return [
    ''      => [
        // 全局跨域
        AccessControlMIddleWare::class,
        // 请求预处理
        RequestMiddleWare::class

    ],
    'api'=>[
        // new RadminAuthMiddleware(['admin','user']),
    ],
    'admin' => [

        new RadminAuthMiddleware(['admin']),
        // 后台请求预处理
        AdminRequest::class,
        // 管理员操作日志
        AdminLog::class,
        // 数据安全
        AdminSecurity::class,
    ],

];
