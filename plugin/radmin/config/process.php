<?php
/** @noinspection PhpRedundantOptionalArgumentInspection */

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
