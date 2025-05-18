<?php

/**
 * UnauthorizedHttpException represents an "Unauthorized" HTTP exception with status code 401.
 *
 * Use this exception to indicate that a client needs to authenticate via WWW-Authenticate header
 * to perform the requested action.
 *
 * If the client is already authenticated and is simply not allowed to
 * perform the action, consider using a 403 [[ForbiddenHttpException]]
 * or 404 [[NotFoundHttpException]] instead.
 *
 * @link   https://tools.ietf.org/html/rfc7235#section-3.1
 * @author Tinywan(ShaoBo Wan)
 * @date   2022/3/6 14:14
 * @since  1.0
 */

declare(strict_types=1);

namespace Radmin\exception;

use Throwable;

class TokenException extends Exception
{
    /**
     * HTTP 状态码
     */
    public int $statusCode = 200;

    /**
     * 错误消息.
     */
    public string $errorMessage = 'TokenException';


    public function __construct($errorMessage = null, $code = 0,array $data = [], ?Throwable $previous = null)
    {
        $params['data'] = $data;
        $params['errorCode'] = $code;
        parent::__construct($errorMessage, $params, $previous);
    }
}
