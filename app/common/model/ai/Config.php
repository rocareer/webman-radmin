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
 * AI配置
 */
class Config extends BaseModel
{
    // 表名
    protected $name = 'ai_config';

    protected static array $values = [
        'int' => [
            'ai_accurate_hit',
            'ai_short_memory',
            'ai_effective_match_kbs',
            'ai_effective_kbs_count',
            'ai_irrelevant_determination',
            'ai_short_memory_percent',
            'ai_prompt_percent',
            'ai_kbs_percent',
            'ai_user_input_percent',
            'ai_response_percent',
            'ai_gift_tokens',
            'ai_score_exchange_tokens',
            'ai_money_exchange_tokens',
        ],
    ];
	
	/**
	 * 事件 todo for buildadmin
	 * By albert  2025/04/21 23:44:15
	 * 某些情况下给前端送的不是数值类型 强制转换
	 * @param $model
	 *
	 
	 */
	public static function onAfterRead($model)
	{
		if (in_array($model->getData('name'), self::$values['int'])) {
			// 确保转换为数值类型
			$model->setAttr('value', (float)$model->value);
			// 同时设置原始数据避免后续处理覆盖
			$model->setData('value', (float)$model->value);
		}
	}
}