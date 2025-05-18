<?php

namespace plugin\radmin\app\admin\controller\auth;

use plugin\radmin\app\admin\model\AdminLog as AdminLogModel;
use plugin\radmin\app\common\controller\Backend;
use plugin\radmin\support\member\Member;
use Radmin\orm\Rdb;
use Radmin\Response;
use Radmin\util\SystemUtil;
use Throwable;

class AdminLog extends Backend
{
    /**
     * @var object
     * @phpstan-var AdminLogModel
     */
    protected object $model;

    protected string|array $preExcludeFields = ['create_time', 'admin_id', 'username'];

    protected string|array $quickSearchField = ['title'];

    protected array $withJoinTable = ['admin'];


    public function initialize(): void
    {
        parent::initialize();
        $this->model = new AdminLogModel();
    }

    /**
     * 查看
     * @throws Throwable
     */
    public function index(): Response
    {
        if ($this->request->input('select')) {
            return $this->select();
        }

        list($where, $alias, $limit, $order) = $this->queryBuilder();
        if (!Member::hasRole('super')) {
            $where[] = ['admin_id', '=', $this->request->member->id];
        }
        $res = Rdb::name('admin_log')->
        withJoin($this->withJoinTable, $this->withJoinType)
            ->alias($alias)
            ->where($where)
            ->order($order)
            ->paginate($limit);
        return $this->success('', [
            'list'   => $res->items(),
            'total'  => $res->total(),
            'remark' => SystemUtil::get_route_remark(),
        ]);
    }
}