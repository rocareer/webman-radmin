<?php

namespace plugin\radmin\app\admin\controller\security;

use plugin\radmin\app\admin\model\DataRecycleLog as DataRecycleLogModel;
use plugin\radmin\app\common\controller\Backend;
use plugin\radmin\extend\ba\TableManager;
use Radmin\orm\Rdb;
use Radmin\Response;
use Throwable;

class DataRecycleLog extends Backend
{
    /**
     * @var object
     * @phpstan-var DataRecycleLogModel
     */
    protected object $model;

    // 排除字段
    protected string|array $preExcludeFields = [];

    protected string|array $quickSearchField = 'recycle.name';

    protected array $withJoinTable = ['recycle', 'admin'];

    public function initialize():void
    {
        parent::initialize();
        $this->model = new DataRecycleLogModel();
    }

    /**
     * 还原
     * @throws Throwable
     */
    public function restore(): Response
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
                $recycleData = json_decode($row['data'], true);
                if (is_array($recycleData) && Rdb::connect(TableManager::getConnection($row->connection))->name($row->data_table)->insert($recycleData)) {
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
            return $this->error(__('No rows were restore'));
        }
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
            ->where('data_recycle_log.id', $id)
            ->find();
        if (!$row) {
            return $this->error(__('Record not found'));
        }
        $data = $this->jsonToArray($row['data']);
        if (is_array($data)) {
            foreach ($data as $key => $item) {
                $data[$key] = jsonToArray($item);
            }
        }
        $row         = $row->toArray();
        $row['data'] = $data;

        return $this->success('', [
            'row' => $row
        ]);
    }

    protected function jsonToArray($value = '')
    {
        if (!is_string($value)) {
            return $value;
        }
        $data = json_decode($value, true);
        if (($data && is_object($data)) || (is_array($data) && !empty($data))) {
            return $data;
        }
        return $value;
    }
}