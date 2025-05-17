<?php


namespace plugin\radmin\app\common\model\ai;

use plugin\radmin\app\common\model\BaseModel;
use think\model\relation\BelongsTo;

/**
 * Session
 */
class Session extends BaseModel
{
    // 表名
    protected $name = 'ai_session';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;
    protected $updateTime         = false;

    public function getTitleAttr($value): string
    {
        return $value ? $value : '无标题';
    }

    public function aiUser(): BelongsTo
    {
        return $this->belongsTo(AiUser::class, 'user_id', 'id');
    }
}