<?php
/*
 *
 *  * Copyright (c) 2024 - 2026 Albert@Rocareer.com
 *  *
 *  * This program is a derivative work migrated from BuildAdmin & Webman
 *  * Licensed under MIT License with additional conditions:
 *  * - Free to copy, modify and distribute
 *  * - **Commercial use is strictly prohibited**
 *  * - Must retain original copyright and this license notice
 *  * - Provided "AS IS" without warranty
 *  *
 *  * Full license: https://opensource.org/licenses/MIT
 *
 */

use app\middleware\RadminAuthMiddleware;
use Webman\Route;


// 关键：禁用默认的静态文件处理
//Route::disableDefaultRoute();


Route::group('/app/radmin', function() {
    /**
     * 配置测试
     * 自定义管理后台路由
     * Path: /app/radmin/admin/[{path:.*\..+}] 匹配包含点的路径（如controller. action格式）
     *
     */
	Route::any('/admin/[{path:.*\..+}]', function() {
		$request=request();
		// 获取请求路径并移除固定前缀
		$path    = $request->path();
		$newPath = str_replace('.', '/', $path);
		if($request->queryString()) {
			$queryString = '?'.$request->queryString();
		}
		else {
			$queryString = '';
		}
		$newUrl = $newPath.$queryString;
		return redirect($newUrl, 308);
	});

    // test
    Route::any('/test/auth/[{path:.+}]',function (){

    })->middleware(
        [new RadminAuthMiddleware(['admin'])]
    );
});








