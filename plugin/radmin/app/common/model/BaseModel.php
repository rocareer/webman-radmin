<?php
/** @noinspection PhpMissingFieldTypeInspection */

/**
 * File:        BaseModel.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/11 00:27
 * Description:
 *
 * Copyright [2014-2026] [https://rocareer.com]
 * Licensed under the Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
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
