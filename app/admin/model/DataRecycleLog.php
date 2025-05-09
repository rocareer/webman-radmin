<?php
/** @noinspection PhpMissingFieldTypeInspection */


namespace app\admin\model;

use app\common\model\BaseModel;
use think\model\relation\BelongsTo;

/**
 * DataRecycleLog 模型
 */
class DataRecycleLog extends BaseModel
{
    protected $name = 'security_data_recycle_log';

    protected $autoWriteTimestamp = true;
    protected $updateTime         = false;
    protected $validate = false;

    public function recycle(): BelongsTo
    {
        return $this->belongsTo(DataRecycle::class, 'recycle_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}