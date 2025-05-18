<?php

return [
    // 默认角色
    'default_role' => 'admin',

    'container'    => [

    ],

    // 角色配置
    'roles'        => [
        'admin' => [
            'service'       => 'plugin\radmin\support\member\admin\AdminService',
            'model'         => 'plugin\radmin\support\member\admin\AdminModel',
            'authenticator' => 'plugin\radmin\support\member\admin\AdminAuthenticator',
            'state'         => 'plugin\radmin\support\member\admin\AdminState',
        ],
        'user'  => [
            'service'       => 'plugin\radmin\support\member\user\UserService',
            'model'         => 'plugin\radmin\support\member\user\UserModel',
            'authenticator' => 'plugin\radmin\support\member\user\UserAuthenticator',
            'state'         => 'plugin\radmin\support\member\user\UserState',
        ],
    ],

    // 缓存配置
    'cache'        => [
        'prefix' => 'member:',
        'ttl'    => 3600, // 缓存时间，单位秒
    ],

    // 登录配置
    'login'        => [
        'token_ttl'          => 86400, // 令牌有效期，单位秒
        'refresh_token_ttl'  => 604800, // 刷新令牌有效期，单位秒
        'max_login_attempts' => 5, // 最大登录尝试次数
        'lockout_time'       => 900, // 锁定时间，单位秒
    ],

    // 日志表配置
    'log_tables'   => [
        'login'     => 'user_login_log',
        'password'  => 'user_password_log',
        'operation' => 'user_operation_log',
    ],

    'definitions_builder' => [
        // 启用编译缓存
        'enableCompilation' => true,
        'compilationPath'   => runtime_path() . '/di',
        // 启用注解
        'useAnnotations'    => true,
        // 启用自动装配
        'useAutowiring'     => true,
    ],


];
