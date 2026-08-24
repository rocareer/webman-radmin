<?php
/** @noinspection PhpMissingFieldTypeInspection */

/**
 * File:        BaseModel.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/11 00:27
 * Description:
 *
 * Copyright (c) Rocareer Team. All rights reserved.
 * Author: albert@rocareer.com
 */



namespace plugin\radmin\app\common\model;

use Radmin\orm\Model as ThinkModel;

class BaseModel extends ThinkModel
{
    protected $strict = false;
    /**
     * @var bool
     */
    protected $autoWriteTimestamp = true;

    protected $type = [
        'update_time' => 'integer',
        'create_time' => 'integer',
    ];



}
