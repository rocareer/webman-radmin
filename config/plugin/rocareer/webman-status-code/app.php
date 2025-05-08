<?php


use support\StatusCode;

return [
    'enable' => true,


    "status_code_class" => new StatusCode(), // ErrorCode 类文件

    "system_number" => 201, // 系统标识
    "start_min_number" => 10000,// 错误码生成范围 例如 10000-99999

    // 扫描错误码
    'status_scan_path' => array_merge([
        radmin_base(),

//        config_path(),
//        base_path() . '/process',
//        base_path() . '/support',
//        base_path() . '/resource',
//        base_path() . '/.env',
//        radmin_base() . '/support',
//        radmin_base() . '/middleware'
    ])
];