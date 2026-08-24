<?php
/**
 * File:        middleware.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/12 02:37
 * Description:
 *
 * Copyright (c) Rocareer Team. All rights reserved.
 * Author: albert@rocareer.com
 */

use plugin\radmin\middleware\AccessControlMiddleWare;
use plugin\radmin\middleware\AdminLog;
use plugin\radmin\middleware\AdminSecurity;
use plugin\radmin\middleware\RadminAuthMiddleware;
use plugin\radmin\middleware\RequestContextMiddleWare;
use plugin\radmin\middleware\RequestMiddleWare;

return [
    ''      => [
        // 全局跨域
        AccessControlMiddleWare::class,
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
