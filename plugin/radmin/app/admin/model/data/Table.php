<?php

namespace plugin\radmin\app\admin\model\data;

use plugin\radmin\app\common\model\BaseModel;

/**
 * Table
 */
class Table extends BaseModel
{
    // 表名
    protected $name = 'data_table';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;
    protected $updateTime = false;
    protected $json = ['columns'];


    protected $jsonAssoc = true;

    public function getName():string
    {
        return $this->name;
    }


}