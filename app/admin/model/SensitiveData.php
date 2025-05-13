<?php

namespace app\admin\model;

use app\common\model\BaseModel;

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