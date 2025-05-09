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

/**
 * File      auth.php
 * Author    albert@rocareer.com
 * Time      2025-04-27 23:36:45
 * Describe  auth.php
 */


return [

    'state'   => [
        'cache_time' => 86400,
        'store'      => 'member-cache',
        'prefix'     => 'state-'
    ],

    // 登录限制配置
    'login'   => [
        // 管理员配置
        'admin' => [
            'login_failure_retry' => 10,
            'login_lock_time'     => 3600,
            'sso'                 => false,            // 是否单点登录
            'token_keep_time'     => 259200,           // token保持时间(秒)
            'captcha'             => false,              //

        ],
        // 用户配置
        'user'  => [
            'maxRetry' => 10,
            'sso'      => false,
            'keepTime' => 259200,
            'captcha'  => false              //
        ]
    ],

    // 免认证路径配置
    'exclude' => [
        // 管理后台免认证路径
        '/admin/login',
        '/admin/captcha',

        // API免认证路径
        '/api/common/refreshToken', // 刷新token

        '/api/user/checkIn',

        // 兼容旧路径
        '/admin/Index/login',

        // 测试路由
        // '/test/auth/index',
        '/test/auth/login',

        // 终端免验证
        '/admin/ajax/terminal'
    ],


];