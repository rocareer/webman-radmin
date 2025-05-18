<?php

namespace plugin\radmin\app\admin\controller\crud;

use plugin\radmin\app\admin\model\CrudLog;
use plugin\radmin\app\common\controller\Backend;
use plugin\radmin\support\member\Member;
use plugin\radmin\support\StatusCode;
use Radmin\exception\BusinessException;

/**
 * crud记录
 *
 */
class Log extends Backend
{
    /**
     * Log模型对象
     * @var object
     * @phpstan-var CrudLog
     */
    protected object $model;

    protected string|array $preExcludeFields = ['id', 'create_time'];

    protected string|array $quickSearchField = ['id', 'table_name', 'comment'];

    protected array $noNeedPermission = ['index'];

    public function initialize():void
    {
        parent::initialize();
        $this->model = new CrudLog();


        if (!Member::check('crud/crud/index')) {
         throw new BusinessException('无权操作',StatusCode::NO_PERMISSION);
        }
    }

}