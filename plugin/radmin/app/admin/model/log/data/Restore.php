<?php

namespace plugin\radmin\app\admin\model\log\data;

use plugin\radmin\app\admin\model\Admin;
use plugin\radmin\app\common\model\BaseModel;
use Radmin\orm\Rdb;
use think\model\relation\BelongsTo;

/**
 * Restore
 */
class Restore extends BaseModel
{
    // 表名
    protected $name = 'log_data_restore';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;
    protected $updateTime         = false;
    protected $type               = [
        'create_time' => 'integer'
    ];


    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

    /**
     *
     * @param $data
     * @return   void
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/18 02:03
     */
    public function record($data): void
    {
        try {
            Rdb::startTrans();
            $saveData = [
                'admin_id'   => $data['admin_id'],
                'version'    => $data['version'],
                'table_name' => $data['table_name']
            ];
            $this->create($saveData);
            Rdb::commit();
        } catch (\Exception $e) {
            Rdb::rollback();
            throw new \Exception($e->getMessage());
        }
    }
}