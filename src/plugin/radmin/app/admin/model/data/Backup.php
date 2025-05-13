<?php

namespace app\admin\model\data;

use plugin\radmin\app\common\model\BaseModel;

/**
 * Backup
 */
class Backup extends BaseModel
{
    // 表名
    protected $name = 'data_backup';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;
    protected $updateTime = false;

    // 字段类型转换
    protected $type = [
        'run_time' => 'integer',
        'create_time' => 'integer',
    ];



    public function setFileAttr($value): string
    {
        return $value == full_url($value, true) ? '' : $value;
    }


    public function dataTable(): \think\model\relation\BelongsTo
    {
        return $this->belongsTo(\app\admin\model\data\Table::class, 'table_name', 'name');
    }


}