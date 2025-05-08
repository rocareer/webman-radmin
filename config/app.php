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

use support\Request;

return [
    'debug' => true,
    'error_reporting' => E_ALL,
    'default_timezone' => 'Asia/Shanghai',
    'request_class' => Request::class,
    'public_path' => base_path() . DIRECTORY_SEPARATOR . 'public',
    'runtime_path' => base_path(false) . DIRECTORY_SEPARATOR . 'runtime',
    'controller_suffix' => 'Controller',
    'controller_reuse' => false,

    // http cache
    'http_cache'     => getenv('HTTP_CACHE', false),
    'http_cache_ttl' => getenv('HTTP_CACHE_TTL', 0),

    // request log
    'request'=>[
        'log'=>[
            'enable'=>true,
            'channel'=>'R-request'
        ]
    ]
];
