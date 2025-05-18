<?php

namespace plugin\radmin\app\admin\controller;

use plugin\radmin\app\common\controller\Backend;

/**
 * 测试管理
 */
class Test extends Backend
{
    /**
     * Test模型对象
     * @var object
     * @phpstan-var \plugin\radmin\app\admin\model\Test
     */
    protected object $model;

    protected array|string $preExcludeFields = ['id', 'update_time', 'create_time'];

    protected string|array $quickSearchField = ['id'];

    public function initialize():void
    {
        parent::initialize();
        $this->model = new \plugin\radmin\app\admin\model\Test();
    }


    /**
     * 若需重写查看、编辑、删除等方法，请复制 @see \plugin\radmin\app\admin\library\traits\Backend 中对应的方法至此进行重写
     */
}