<?php


namespace plugin\radmin\app\common\model\ai;

use plugin\radmin\app\common\model\BaseModel;

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