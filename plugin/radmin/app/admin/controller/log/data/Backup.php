<?php

namespace plugin\radmin\app\admin\controller\log\data;

use plugin\radmin\app\common\controller\Backend;
use Radmin\Response;
use Radmin\util\SystemUtil;
use Throwable;

/**
 * 数据备份日志
 */
class Backup extends Backend
{
    /**
     * Backup模型对象
     * @var object
     * @phpstan-var \plugin\radmin\app\admin\model\log\data\Backup
     */
    protected object $model;

    protected array|string $preExcludeFields = ['id', 'create_time'];

    protected array $withJoinTable = ['admin'];

    protected string|array $quickSearchField = ['id'];

    public function initialize(): void
    {
        parent::initialize();
        $this->model = new \plugin\radmin\app\admin\model\log\data\Backup();
    }

    /**
     * 查看
     * @throws Throwable
     */
    public function index(): Response
    {
        // 如果是 select 则转发到 select 方法，若未重写该方法，其实还是继续执行 index
        if ($this->request->input('select')) {
            return $this->select();
        }

        /**
         * 1. withJoin 不可使用 alias 方法设置表别名，别名将自动使用关联模型名称（小写下划线命名规则）
         * 2. 以下的别名设置了主表别名，同时便于拼接查询参数等
         * 3. paginate 数据集可使用链式操作 each(function($item, $key) {}) 遍历处理
         */
        list($where, $alias, $limit, $order) = $this->queryBuilder();
        $res = $this->model
            ->withJoin($this->withJoinTable, $this->withJoinType)
            ->alias($alias)
            ->where($where)
            ->order($order)
            ->paginate($limit);
        $res->visible(['admin' => ['username']]);

        return $this->success('', [
            'list'   => $res->items(),
            'total'  => $res->total(),
            'remark' => SystemUtil::get_route_remark(),
        ]);
    }


    /**
     * 若需重写查看、编辑、删除等方法，请复制 @see \plugin\radmin\app\admin\library\traits\Backend 中对应的方法至此进行重写
     */
}