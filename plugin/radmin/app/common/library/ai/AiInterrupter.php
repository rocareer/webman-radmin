<?php


namespace plugin\radmin\app\common\library\ai;

use think\Exception;

/**
 * 中断器，本异常类将被 catch 且不额外抛出错误·
 */
class AiInterrupter extends Exception
{
    public function __construct(protected $message, protected $code = 0, protected $data = [])
    {
        parent::__construct($message, $code);
    }
}