<?php

/**
 * @desc 路由地址不存在异常类
 *
 * @see https://tools.ietf.org/html/rfc7231#section-6.5.3
 * @author Tinywan(ShaoBo Wan)
 * @email 756684177@qq.com
 * @date 2022/3/6 14:14
 */

declare(strict_types=1);

namespace Radmin\exception;

class RouteNotFoundException extends Exception
{
    /**
     * HTTP 状态码
     */
    public int $statusCode = 404;

    /**
     * 错误消息.
     */
    public string $errorMessage = '路由地址不存在';
}
