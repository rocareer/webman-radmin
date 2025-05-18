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

use plugin\radmin\support\StatusCode;
use Throwable;

class UnauthorizedHttpException extends Exception
{
    /**
     * HTTP 状态码
     */
    public int $statusCode = 200;

    /**
     * 错误消息.
     */
    public string $errorMessage = 'Unauthorized';


    public function __construct(?string $errorMessage = null, $code = 0,$needLogin = false,array $data = [], ?Throwable $previous = null)
    {

        $errorMessage=$errorMessage??$this->errorMessage;
        $params['errorCode'] = $code;
        $params['data'] = $data;
        if ($needLogin) {
            $params['data']['type'] = 'need login';
            $params['errorCode']    = StatusCode::NEED_LOGIN;
        }
        parent::__construct($errorMessage, $params, $previous);
    }
}
