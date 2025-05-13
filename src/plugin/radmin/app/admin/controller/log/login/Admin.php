<?php

namespace app\admin\controller\log\login;

use app\common\controller\Backend;
use plugin\radmin\support\Response;

/**
 * 管理员登录
 */
class Admin extends Backend
{
    /**
     * Admin模型对象
     * @var object
     * @phpstan-var \app\admin\model\log\login\Admin
     */
    protected object $model;

    protected array|string $preExcludeFields = ['id', 'create_time'];

    protected string|array $quickSearchField = ['id'];

    public function initialize():void
    {
        parent::initialize();
        $this->model = new \app\admin\model\log\login\Admin();
    }


    /**
     * 若需重写查看、编辑、删除等方法，请复制 @see \app\admin\library\traits\Backend 中对应的方法至此进行重写
     */
}