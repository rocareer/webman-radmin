<?php
/*
 *
 *  * // +----------------------------------------------------------------------
 *  * // | Rocareer [ ROC YOUR CAREER ]
 *  * // +----------------------------------------------------------------------
 *  * // | Copyright (c) 2014~2025 Albert@rocareer.com All rights reserved.
 *  * // +----------------------------------------------------------------------
 *  * // | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 *  * // +----------------------------------------------------------------------
 *  * // | Author: albert <Albert@rocareer.com>
 *  * // +----------------------------------------------------------------------
 *
 */

/**
 * File      AccessControl.php
 * Author    albert@rocareer.com
 * Time      2025-04-13 11:37:37
 * Describe  AccessControl.php
 */

namespace app\middleware;

use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class AdminRequest implements MiddlewareInterface
{
    /**
     * @param Request  $request
     * @param callable $handler
     * @return Response
     */
    public function process(Request $request, callable $handler): Response
    {



        return $handler($request);
    }
}