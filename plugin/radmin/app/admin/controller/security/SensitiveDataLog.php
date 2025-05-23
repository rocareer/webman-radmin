<?php

namespace plugin\radmin\app\admin\controller\security;

use plugin\radmin\app\admin\model\SensitiveDataLog as SensitiveDataLogModel;
use plugin\radmin\app\common\controller\Backend;
use plugin\radmin\extend\ba\TableManager;
use Radmin\orm\Rdb;
use Radmin\Response;
use Radmin\util\SystemUtil;
use Throwable;

class SensitiveDataLog extends Backend
{
    /**
     * @var object
     * @phpstan-var SensitiveDataLogModel
     */
    protected object $model;

    // 排除字段
    protected string|array $preExcludeFields = [];

    protected string|array $quickSearchField = 'sensitive.name';

    protected array $withJoinTable = ['sensitive', 'admin'];

    public function initialize():void
    {
        parent::initialize();
        $this->model = new SensitiveDataLogModel();
    }

    /**
     * 查看
     * @throws Throwable
     */
    public function index(): ?Response
    {
        if ($this->request->input('select')) {
       return $this->select();
        }

        list($where, $alias, $limit, $order) = $this->queryBuilder();
        $res = $this->model
            ->withJoin($this->withJoinTable, $this->withJoinType)
            ->alias($alias)
            ->where($where)
            ->order($order)
            ->paginate($limit);

        foreach ($res->items() as $item) {
            $item->id_value = $item['primary_key'] . '=' . $item->id_value;
        }

     return $this->success('', [
            'list'   => $res->items(),
            'total'  => $res->total(),
            'remark' => SystemUtil::get_route_remark(),
        ]);
    }

    /**
     * 详情
     * @throws Throwable
     */
    public function info(): Response
    {
        $pk  = $this->model->getPk();
        $id  = $this->request->input($pk);
        $row = $this->model
            ->withJoin($this->withJoinTable, $this->withJoinType)
            ->where('sensitive_data_log.id', $id)
            ->find();
        if (!$row) {
         return $this->error(__('Record not found'));
        }

     return $this->success('', [
            'row' => $row
        ]);
    }

    /**
     * 回滚
     * @throws Throwable
     */
    public function rollback(): Response
    {
        $ids  = $this->request->input('ids', []);
        $data = $this->model->where('id', 'in', $ids)->select();
        if (!$data) {
         return $this->error(__('Record not found'));
        }

        $count = 0;
        $this->model->startTrans();
        try {
            foreach ($data as $row) {
                if (Rdb::connect(TableManager::getConnection($row->connection))->name($row->data_table)->where($row->primary_key, $row->id_value)->update([
                    $row->data_field => $row->before
                ])) {
                    $row->delete();
                    $count++;
                }
            }
            $this->model->commit();
        } catch (Throwable $e) {
            $this->model->rollback();
         return $this->error($e->getMessage());
        }

        if ($count) {
         return $this->success();
        } else {
         return $this->error(__('No rows were rollback'));
        }
    }
}