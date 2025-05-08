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

use Throwable;
use exception;
use app\common\model\BaseModel;
use support\think\Db;
use app\common\library\ai\Helper;

/**
 * AiUser
 */
class AiUser extends BaseModel
{
    // 表名
    protected $name = 'ai_user';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;
    protected $updateTime         = false;

    // 字段类型转换
    protected $type = [
        'last_use_time' => 'int',
        'create_time'   => 'int',
        'update_time'   => 'int'
    ];

    protected $append = [
        'nickname'
    ];

    public static function onBeforeInsert($model)
    {
        // 重复的user_id
        $exist = AiUser::where('user_type', $model->user_type)
            ->where('user_id', $model->user_id)
            ->find();
        if ($exist) {
            throw new Exception('绑定的用户已经是AI会员了');
        }
    }

    public static function onAfterInsert($model)
    {
        // 赠送 tokens
        $aiConfig = Helper::getConfig();
        if ($aiConfig['ai_gift_tokens']) {
            UserTokens::create([
                'ai_user_id' => $model->id,
                'tokens'     => $aiConfig['ai_gift_tokens'],
                'memo'       => '新用户赠送tokens~'
            ]);
        }
    }

    public static function onBeforeUpdate($model)
    {
        $exist = AiUser::where('user_type', $model->user_type)
            ->where('user_id', $model->user_id)
            ->where('id', '<>', $model->id)
            ->find();
        if ($exist) {
            throw new Exception('绑定的用户已经是AI会员了');
        }
    }

    public function getNicknameAttr($value, $row)
    {
        if (!$row['user_id']) return '-';

        if ($row['user_type'] == 'user') {
            return Db::name('user')->where('id', $row['user_id'])->value('nickname');
        } elseif ($row['user_type'] == 'admin') {
            return Db::name('admin')->where('id', $row['user_id'])->value('nickname');
        }
        return '';
    }
}