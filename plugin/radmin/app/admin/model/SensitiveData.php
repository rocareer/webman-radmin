<?php

namespace plugin\radmin\app\admin\model;

use plugin\radmin\app\common\model\BaseModel;

/**
 * SensitiveData 模型
 */
class SensitiveData extends BaseModel
{
    protected $name = 'security_sensitive_data';

    protected $autoWriteTimestamp = true;
    protected $validate = false;
    protected $type = [
        'data_fields' => 'array',
        'update_time'=>'integer',
        'create_time'=>'integer'
    ];
}