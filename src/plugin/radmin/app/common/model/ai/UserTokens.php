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

use plugin\radmin\app\common\model\BaseModel;
use Throwable;

/**
 * UserTokens
 */
class UserTokens extends BaseModel
{
    // 表名
    protected $name = 'ai_user_tokens';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;
    protected $updateTime         = false;

    /**
     * 入库前
     * @throws Throwable
     */
    public static function onBeforeInsert($model)
    {
        $user = AiUser::where('id', $model->ai_user_id)->lock(true)->find();
        if (!$user) {
            throw new Exception('AI会员找不到了');
        }
        if (!$model->memo) {
            throw new Exception('变更备注不能为空');
        }
        $model->before = $user->tokens;

        $user->tokens += $model->tokens;
        $user->save();

        $model->after = $user->tokens;
    }

    public static function onBeforeDelete(): bool
    {
        return false;
    }


    public function aiUser(): \think\model\relation\BelongsTo
    {
        return $this->belongsTo(\app\common\model\ai\AiUser::class, 'ai_user_id', 'id');
    }
}