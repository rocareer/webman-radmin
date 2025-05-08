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

/**
 * File      BaseModel.php
 * Author    albert@rocareer.com
 * Time      2025-04-15 02:42:29
 * Describe  BaseModel.php
 */

namespace app\common\model;

use support\think\Model as ThinkModel;

class BaseModel extends ThinkModel
{
    protected $strict = false;
	protected $autoWriteTimestamp = true;


	protected $type = [
		'update_time' => 'integer',
		'create_time' => 'integer',

	];
}