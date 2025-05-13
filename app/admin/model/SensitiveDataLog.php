<?php

namespace app\admin\model;

use app\common\model\BaseModel;
use think\model\relation\BelongsTo;

/**
 * SensitiveDataLog 模型
 */
class SensitiveDataLog extends BaseModel
{
    protected $name = 'security_sensitive_data_log';

    protected $autoWriteTimestamp = true;
    protected $updateTime         = false;
    protected $validate = false;


    public function sensitive(): BelongsTo
    {
        return $this->belongsTo(SensitiveData::class, 'sensitive_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}