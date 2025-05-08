<?php
/**
 * File      phinx.php
 * Author    albert@rocareer.com
 * Time      2025-04-25 23:17:39
 * Describe  phinx.php
 */
return [
    "paths" => [
        "migrations" => "plugin/radmin/database/migrations",
        "seeds"      => "plugin/radmin/database/seeds"
    ],
    "table_prefix" => "ra_",
    "environments" => [
        "default_migration_table" => getenv('THINKORM_DEFAULT_PREFIX')."migrations",
        "default_environment"     => "dev",
        "dev" => [
            "adapter" => 'mysql',
            "host"    => getenv('THINKORM_DEFAULT_HOST', ''),
            "name"    => getenv('THINKORM_DEFAULT_DATABASE', ''),
            "user"    => getenv('THINKORM_DEFAULT_USERNAME', ''),
            "pass"    => getenv('THINKORM_DEFAULT_PASSWORD', ''),
            "port"    => getenv('THINKORM_DEFAULT_PORT', 3306),
            "charset" => getenv('THINKORM_DEFAULT_CHARSET', 'utf8'),
            "prefix" => getenv('THINKORM_DEFAULT_PREFIX', 'ra_'), // 确保这里有前缀


        ],
        'production' => [
            'adapter' => 'mysql',
            'host' => '127.0.0.1',
            'name' => 'your_production_database_name',
            'user' => 'root',
            'pass' => 'your_password',
            'port' => '3306',
            'charset' => 'utf8',
            "prefix" => "rb_", // 确保这里有前缀
        ],
    ]
];