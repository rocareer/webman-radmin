<?php

namespace app\admin\model;

use app\common\model\BaseModel;

/**
 * Log
 */
class CrudLog extends BaseModel
{
    // 表名
    protected $name = 'crud_log';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;
    protected $updateTime         = false;

    protected $type = [
        'table'  => 'array',
        'fields' => 'array',
        'create_time'=>'integer'
    ];

}