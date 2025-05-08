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

namespace plugin\radmin\middleware;

use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class AdminRequest implements MiddlewareInterface
{
    public function process(Request $request, callable $handler): Response
    {

        $controller              = explode('\\', $request->controller);
        $controller              = array_slice($controller, -2);
        $request->controllerName = strtolower(implode('/', $controller));


        if ($request->get('initValue') !== null) {
            $initValue = $request->get('initValue');
            if (!is_array($initValue)){
                $initValue = explode(',', $initValue);
                $request->setGet('initValue', $initValue);
            }
        }

        return $handler($request);
    }
}