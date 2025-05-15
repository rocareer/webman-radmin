<?php
/**
 * File:        middleware.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/12 02:37
 * Description:
 *
 * Copyright [2014-2026] [https://rocareer.com]
 * Licensed under the Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */

use plugin\radmin\middleware\AccessControlMIddleWare;
use plugin\radmin\middleware\AdminLog;
use plugin\radmin\middleware\AdminSecurity;
use plugin\radmin\middleware\RadminAuthMiddleware;
use plugin\radmin\middleware\RequestContextMiddleWare;
use plugin\radmin\middleware\RequestMiddleWare;

return [
    ''      => [
        // 全局跨域
        AccessControlMIddleWare::class,
        // 请求预处理
        RequestMiddleWare::class,
        RequestContextMiddleWare::class

    ],
    'api'   => [
        new RadminAuthMiddleware('user'),
    ],
    'admin' => [

        new RadminAuthMiddleware('admin'),

        // 管理员操作日志
        AdminLog::class,
        // 数据安全
        AdminSecurity::class,
    ],
    'user'  => [
        new RadminAuthMiddleware('user'),
    ],

];
