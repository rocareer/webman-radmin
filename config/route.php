<?php
/**
 * File:        route.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/12 02:35
 * Description:
 *
 * Copyright [2014-2026] [https://rocareer.com]
 * Licensed under the Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */

use app\user\controller\Account;
use app\user\controller\Index;
use Webman\Route;


// 关键：禁用默认的静态文件处理
//Route::disableDefaultRoute();


/**
 * Api 路由组 将原buildadmin 前台api路由改为单独user应用 方便后续扩展
 */
Route::group('/api', function () {

    Route::group('/user', function () {
        Route::any('/checkIn', [Index::class, 'login']);
        Route::any('/logout', [Index::class, 'logout']);
    });

    Route::group('/account', function () {
        Route::any('/balance', [Account::class, 'balance']);
        Route::any('/profile', [Account::class, 'profile']);
        Route::any('/overview', [Account::class, 'overview']);
        Route::any('/integral', [Account::class, 'integral']);
        Route::any('/changePassword', [Account::class, 'changePassword']);
    });
});








