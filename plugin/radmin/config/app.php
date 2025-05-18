<?php
/** @noinspection PhpRedundantOptionalArgumentInspection */
/** @noinspection SpellCheckingInspection */

/**
 * This file is part of webman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

use Radmin\Request;

return [
    'debug'             => getenv('APP_DEBUG', true),
    'error_reporting'   => E_ALL,
    'default_timezone'  => getenv('DEFAULT_TIMEZONE', 'Asia/Shanghai'),
    'controller_suffix' => '',
    'controller_reuse'  => false,

    'request_class'  => Request::class,
    'public_path'    => base_path() . DIRECTORY_SEPARATOR . 'plugin/radmin/public',
    'runtime_path'   => base_path() . DIRECTORY_SEPARATOR . 'plugin/radmin/runtime',
    'app_path'       => base_path() . DIRECTORY_SEPARATOR . 'plugin/radmin/app',

    // http cache 实验功能
    'http_cache'     => getenv('HTTP_CACHE', false),
    'http_cache_ttl' => getenv('HTTP_CACHE_TTL', 0),

    'plugin_url' => '/app/radmin',

    // request log 实验功能
    'request'    => [
        'log' => [
            'enable'  => true,
            'channel' => 'R-request'
        ]
    ]
];
