<?php

namespace Radmin\exception;

use plugin\radmin\support\StatusCode;
use Throwable;

class TerminalException extends Exception
{
    /**
     * 异常类型
     * @var string
     */
    protected $type = 'terminal';

    /**
     * 日志级别
     * @var string
     */
    protected $logLevel = 'warning';

    /**
     * 创建认证异常
     */
    public function __construct(
        string $message = '',
        int $code = StatusCode::AUTH_ERROR,
        array $data = ['data' => 'terminal error'],
        ?string $type = null,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $data, $type, $previous);
    }


}
