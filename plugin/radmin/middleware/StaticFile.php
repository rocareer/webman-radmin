<?php
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

namespace plugin\radmin\middleware;

use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

/**
 * Class StaticFile
 * @package app\middleware
 */
class StaticFile implements MiddlewareInterface
{
    public function process(Request $request, callable $handler): Response
    {
        // Access to files beginning with. Is prohibited
        if (str_contains($request->path(), '/.')) {
            return response('<h1>403 forbidden</h1>', 403);
        }
        /** @var Response $response */
        $response = $handler($request);
        // Add cross domain HTTP header
        /*$response->withHeaders([
            'Access-Control-Allow-Origin'      => '*',
            'Access-Control-Allow-Credentials' => 'true',
        ]);*/
        return $response;
    }
}
