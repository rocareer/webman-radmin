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

namespace app\common\model\ai;

use support\think\Db;
use think\model\relation\BelongsTo;
use app\common\model\BaseModel;

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