<?php
return [
    // 默认驱动
    'default'          => 'jwt',
    'expire_time'      => 200,
    // 'refresh_keep_ttl' => 3600*24*30, // 刷新token时间(秒)
    'refresh_keep_ttl' => 3600, // 刷新token时间(秒)

    // 加密key
    'key'              => 'tcbDgmqLVzuAdNH39o0QnhOisvSCFZ7I',
    // 加密方式
    'algo'             => 'ripemd160',

    'headers' => [
        'admin' => [
            'Authorization',
            'batoken',
            'Batoken',
            'ba-token',
            'Ba-token',
        ],
        'user'  => [
            'Ba-user-token',
            'ba-user-token'
        ]
    ],

    // 驱动配置
    'drivers' => [
        'think-cache'    => [
            'type'   => 'cache',
            'story'  => 'token',

        ],

        // 数据库驱动示例
        'database' => [
            'type'  => 'mysql',
            'table' => 'token',
        ],

        // Firebase JWT驱动
        'jwt'      => [
            'type'   => 'jwt',
            'algo'   => 'HS256', // HS256/RS256/RS384/RS512
            'secret' => getenv('JWT_SECRET_KEY', '000000'),
        ]
    ]
];
