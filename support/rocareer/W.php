<?php


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