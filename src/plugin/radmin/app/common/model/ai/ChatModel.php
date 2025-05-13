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

use app\common\model\BaseModel;

/**
 * ChatModel
 */
class ChatModel extends BaseModel
{
	// 表名
	protected $name = 'ai_chat_model';
	
	// 自动写入时间戳字段
	protected $autoWriteTimestamp = true;
	protected $updateTime         = false;
	
	protected static function onAfterInsert($model)
	{
		if ($model->weigh == 0) {
			$pk = $model->getPk();
			if (strlen($model[$pk]) >= 19) {
				$model->where($pk, $model[$pk])->update(['weigh' => $model->count()]);
			} else {
				$model->where($pk, $model[$pk])->update(['weigh' => $model[$pk]]);
			}
		}
	}
	
	
	
	public function getTokensMultiplierAttr($value): float
	{
		return (float)$value;
	}
}