<?php

namespace app\admin\model;

use app\common\model\BaseModel;


/**
 * UserRule 模型
 * @property int $status 状态:0=禁用,1=启用
 */
class UserRule extends BaseModel
{
    protected $autoWriteTimestamp = true;


    protected $type = [
        'create_time' => 'integer',
        'update_time' => 'integer'
    ];

    protected static function onAfterInsert($model): void
    {
        $pk = $model->getPk();
        $model->where($pk, $model[$pk])->update(['weigh' => $model[$pk]]);
    }

    public function setComponentAttr($value)
    {
        if ($value) $value = str_replace('\\', '/', $value);
        return $value;
    }
}