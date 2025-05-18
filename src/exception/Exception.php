<?php
/**
 * @desc BaseException
 * @author Tinywan(ShaoBo Wan)
 * @email 756684177@qq.com
 * @date 2022/3/6 14:14
 */

declare(strict_types=1);

namespace Radmin\exception;

use Throwable;

class Exception extends \Exception
{
    /**
     * HTTP Response Status Code.
     */
    public int $statusCode = 400;

    /**
     * HTTP Response Header.
     */
    public array $header = [];

    /**
     * Business Error code.
     *
     * @var int|mixed
     */
    public int $errorCode = 0;

    /**
     * Business Error message.
     * @var string
     */
    public string $errorMessage = 'The requested resource is not available or not exists';

    /**
     * Business data.
     * @var array|mixed
     */
    public array $data = [];

    /**
     * Detail Log Error message.
     * @var string
     */
    public string $error = '';

    /**
     * Error message.
     * Exception constructor.
     * @param string         $errorMessage
     * @param array          $params
     * @param Throwable|null $previous
     */
    public function __construct(string $errorMessage = '', array $params = [], ?Throwable $previous = null)
    {
        // 如果没有传递 $previous，尝试获取当前异常
        if ($previous === null) {
            $previous = $this->getCurrentException();
        }

        parent::__construct($errorMessage, $this->statusCode, $previous);
        if (!empty($errorMessage)) {
            $this->errorMessage = $errorMessage;
        }
        if (!empty($error)) {
            $this->error = $error;
        }
        if (!empty($params)) {
            if (array_key_exists('statusCode', $params)) {
                $this->statusCode = $params['statusCode'];
            }
            if (array_key_exists('header', $params)) {
                $this->header = $params['header'];
            }
            if (array_key_exists('errorCode', $params)) {
                $this->errorCode = $params['errorCode'];
            }
            if (array_key_exists('data', $params)) {
                $this->data = $params['data'];
            }
        }
    }

    /**
     * 获取当前异常
     * @return   Exception|null
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/11 19:43
     */
    protected function getCurrentException(): ?Throwable
    {
        // 获取当前异常上下文
        $exception = null;

        if (function_exists('xdebug_get_function_stack')) {
            // 使用 Xdebug 获取调用堆栈
            $stack = xdebug_get_function_stack();
            foreach ($stack as $frame) {
                if (isset($frame['exception']) && $frame['exception'] instanceof Throwable) {
                    $exception = $frame['exception'];
                    break;
                }
            }
        } else {
            // 使用 debug_backtrace 获取调用堆栈
            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            foreach ($trace as $frame) {
                if (isset($frame['args'][0]) && $frame['args'][0] instanceof Throwable) {
                    $exception = $frame['args'][0];
                    break;
                }
            }
        }

        return $exception;
    }

}
