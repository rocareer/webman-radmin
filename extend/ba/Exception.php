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

namespace plugin\radmin\extend\ba;

use Exception as E;

/**
 * BuildAdmin通用异常类
 * catch 到异常后可以直接 return $this->error(__($e->getMessage()), $e->getData(), $e->getCode());
 */
class Exception extends E
{
    public function __construct(protected $message, protected $code = 0, protected $data = [])
    {
        parent::__construct($message, $code);
    }
}