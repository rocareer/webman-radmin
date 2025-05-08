<?php

namespace app\admin\model;

use app\common\model\BaseModel;

/**
 * AdminRule 模型
 * @property int $status 状态:0=禁用,1=启用
 */
class AdminRule extends BaseModel
{
    protected $autoWriteTimestamp = true;

    public function setComponentAttr($value)
    {
        if ($value) $value = str_replace('\\', '/', $value);
        return $value;
    }

}