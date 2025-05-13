<?php


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