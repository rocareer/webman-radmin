<?php
/**
 * File      filesystem.php
 * Author    albert@rocareer.com
 * Time      2025-04-28 02:39:40
 * Describe  filesystem.php
 */
return [
    'default' => 'local', // 默认使用的存储驱动
    'disks'   => [
        'local'  => [
            'type' => 'local', // 本地存储
            'root' => runtime_path('radmin/storage'), // 存储根目录
            'url'        => '/storage',
        ],
        'ftp'    => [
            'type'     => 'ftp', // FTP 存储
            'host'     => 'ftp.example.com',
            'username' => 'your_username',
            'password' => 'your_password',
            'root'     => '/path/to/root',
        ],
        'oss'    => [
            'type'       => 'oss', // 阿里云 OSS
            'access_key' => 'your_access_key',
            'secret_key' => 'your_secret_key',
            'bucket'     => 'your_bucket',
            'endpoint'   => 'your_endpoint',
        ],
        'public' => [
            'type' => 'local', // 本地存储
            'root' => base_path().'/plugin/radmin/public/storage', // 存储根目录
            'url'        => '/storage',
            // 可见性
            'visibility' => 'public',
        ],



        'dev' => [
            'type' => 'local', // 本地存储
            'root' => base_path(), // 存储根目录

        ],
    ],
];
