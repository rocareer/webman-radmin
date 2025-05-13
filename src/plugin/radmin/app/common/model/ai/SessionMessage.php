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

/**
 * SessionMessage
 */
class SessionMessage extends BaseModel
{
    // 表名
    protected $name = 'ai_session_message';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;
    protected $updateTime         = false;

    // 追加属性
    protected $append = [
        'kbsTable',
    ];

    public static function onAfterInsert($model)
    {
        Session::where('id', $model->session_id)
            ->update([
                'last_message_time' => time(),
            ]);
    }


    public function getContentAttr($value): string
    {
        return !$value ? '' : htmlspecialchars_decode($value);
    }

    public function getKbsTableAttr($value, $row): array
    {
        if (!isset($row['kbs'])) return [];
        return [
	        'title' => \app\common\model\ai\KbsContent::whereIn('id', $row['kbs'])->column('title', 'id'),
        ];
    }

    public function getKbsAttr($value): array
    {
        if ($value === '' || $value === null) return [];
        if (!is_array($value)) {
            return explode(',', $value);
        }
        return $value;
    }

    public function setKbsAttr($value): string
    {
        return is_array($value) ? implode(',', $value) : $value;
    }

    public function getConsumptionTokensAttr($value): float
    {
        return (float)$value;
    }

    public function session(): \think\model\relation\BelongsTo
    {
        return $this->belongsTo(\app\common\model\ai\Session::class, 'session_id', 'id');
    }
}