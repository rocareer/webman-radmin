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

use plugin\radmin\support\Log;
use plugin\radmin\support\Request;
use plugin\radmin\app\process\Http;

global $argv;

return [
    'HTTP' => [
        'handler' => Http::class,
        'listen' => 'http://0.0.0.0:9696',
        'count' => getenv('PROCESS_COUNT',cpu_count()),
        'user' => '',
        'group' => '',
        'reusePort' => false,
        'eventLoop' => '',
        'context' => [],
        'constructor' => [
            'requestClass' => Request::class,
            'logger' => Log::channel('default'),
            'appPath' => app_path(),
            'publicPath' => public_path()
        ]
    ]
];
