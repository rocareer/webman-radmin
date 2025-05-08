<?php

namespace app\admin\model;

use plugin\radmin\app\common\model\BaseModel;

/**
 * DataRecycle 模型
 */
class DataRecycle extends BaseModel
{
    protected $name = 'security_data_recycle';

    protected $autoWriteTimestamp = true;
    protected $validate = false;
}