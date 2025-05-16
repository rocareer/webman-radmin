<?php
/** @noinspection PhpUndefinedFieldInspection */

/** @noinspection PhpPossiblePolymorphicInvocationInspection */

namespace plugin\radmin\middleware;

use Exception;
use plugin\radmin\support\Container;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class RequestContextMiddleWare implements MiddlewareInterface
{
    /**
     * @throws Exception
     */
    public function process(Request $request, callable $handler): Response
    {
        $context=Container::get('member.context');
        $response = $handler($request);
        $context->clear();
        return $response;
    }

}
