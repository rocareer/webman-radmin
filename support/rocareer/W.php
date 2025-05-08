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

namespace support\rocareer;


use app\common\library\token\driver;
use think\Facade;

/**
 * Token 门面类
 * @see Driver
 * @method  onStart(int $id , int $pid)
 * @method string getWorkerName(int $id)
 */
class W extends Facade
{
    protected static function getFacadeClass(): string
    {
        return WorkerService::class;
    }
}