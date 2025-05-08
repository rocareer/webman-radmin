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

use middleware\AdminLog;
use middleware\AdminRequest;
use middleware\AdminSecurity;
use middleware\AccessControlMIddleWare;
use middleware\RadminAuthMiddleware;
use middleware\RequestMiddleWare;

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
