<?php
/** @noinspection PhpRedundantOptionalArgumentInspection */
/** @noinspection SpellCheckingInspection */

/**
 * This file is part of webman.
 *
 * Proprietary license (Rocareer Team)
 * Copyright (c) Rocareer Team. All rights reserved.
 * Author: albert@rocareer.com
 *
 * @author    albert <albert@rocareer.com>
 * @copyright Rocareer Team (albert@rocareer.com)
 * @link      http://www.workerman.net/
 * @license   Proprietary (Rocareer Team)
 */

use Radmin\Request;

return [
    'debug'             => env('APP_DEBUG', true),
    'installed'         => true,
    'error_reporting'   => E_ALL,
    'default_timezone'  => env('DEFAULT_TIMEZONE', 'Asia/Shanghai'),
    'controller_suffix' => '',
    'controller_reuse'  => false,

    'request_class'  => Request::class,
    'public_path'    => base_path() . DIRECTORY_SEPARATOR . 'plugin/radmin/public',
    'runtime_path'   => base_path() . DIRECTORY_SEPARATOR . 'plugin/radmin/runtime',
    'app_path'       => base_path() . DIRECTORY_SEPARATOR . 'plugin/radmin/app',

    // http cache 实验功能
    'http_cache'     => env('HTTP_CACHE', false),
    'http_cache_ttl' => env('HTTP_CACHE_TTL', 0),

    'plugin_url' => '/app/radmin',

    // request log 实验功能
    'request'    => [
        'log' => [
            'enable'  => true,
            'channel' => 'R-request'
        ]
    ]
];
