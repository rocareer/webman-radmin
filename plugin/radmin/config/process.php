<?php
/** @noinspection PhpRedundantOptionalArgumentInspection */

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

use Radmin\Http;
use Radmin\Log;
use Radmin\Request;

global $argv;

return [
    'HTTP' => [
        'handler' => Http::class,
        'listen' => 'http://0.0.0.0:9696',
        'count' => 4,
        'user' => '',
        'group' => '',
        'reusePort' => false,
        'eventLoop' => '',
        'context' => [],
        'constructor' => [
            'requestClass' => Request::class,
            'logger' => Log::channel('default'),
            'appPath' => base_path().'/plugin/radmin/app',
            'publicPath' => base_path().'/plugin/radmin/public'
        ]
    ]
];
