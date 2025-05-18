<?php

namespace plugin\radmin\app\admin\model\data;

use plugin\radmin\app\common\model\BaseModel;
use think\model\relation\BelongsTo;

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


    public function dataTable(): BelongsTo
    {
        return $this->belongsTo(Table::class, 'table_name', 'name');
    }

    public function record($data): bool
    {
        try {
            $this->startTrans();
            $result = $this->create([
                'table_name'  => $data['table_name'],
                'file'        => $data['file'],
                'status'      => 1,
                'runnow'      => 1,
                'run_time'    => time(),
                'version'     => $data['version']
            ]);
            $this->commit();
            if ($result) {
                return true;
            }
            return false;
        } catch (\Exception $e) {
            $this->rollback();
            return false;
        }

    }


}