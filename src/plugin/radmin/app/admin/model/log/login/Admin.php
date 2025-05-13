<?php

namespace plugin\radmin\app\admin\model\log\login;

use plugin\radmin\app\common\model\BaseModel;

/**
 * Admin
 */
class Admin extends BaseModel
{
    // 表名
    protected $name = 'log_login_admin';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;
    protected $updateTime = false;

}