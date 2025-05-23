<?php

namespace plugin\radmin\app\admin\controller\security;

use plugin\radmin\app\admin\model\SensitiveData as SensitiveDataModel;
use plugin\radmin\app\common\controller\Backend;
use Radmin\Response;
use Radmin\util\SystemUtil;
use Throwable;

class SensitiveData extends Backend
{
    /**
     * @var object
     * @phpstan-var SensitiveDataModel
     */
    protected object $model;

    // 排除字段
    protected string|array $preExcludeFields = ['update_time', 'create_time'];

    protected string|array $quickSearchField = 'controller';

    public function initialize():void
    {
        parent::initialize();
        $this->model = new SensitiveDataModel();
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
        $res = $this->model
            ->withJoin($this->withJoinTable, $this->withJoinType)
            ->alias($alias)
            ->where($where)
            ->order($order)
            ->paginate($limit);

        foreach ($res->items() as $item) {
            if ($item->data_fields) {
                $fields = [];
                foreach ($item->data_fields as $key => $field) {
                    $fields[] = $field ?: $key;
                }
                $item->data_fields = $fields;
            }
        }

     return $this->success('', [
            'list'   => $res->items(),
            'total'  => $res->total(),
            'remark' => SystemUtil::get_route_remark(),
        ]);
    }

    /**
     * 添加重写
     * @throws Throwable
     */
    public function add(): Response
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if (!$data) {
             return $this->error(__('Parameter %s can not be empty', ['']));
            }

            $data                  = $this->excludeFields($data);
            $data['controller_as'] = str_ireplace('.php', '', $data['controller'] ?? '');
            $data['controller_as'] = strtolower(str_ireplace(['\\', '.'], '/', $data['controller_as']));

            $result = false;
            $this->model->startTrans();
            try {
                // 模型验证
                if ($this->modelValidate) {
                    $validate = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                    if (class_exists($validate)) {
                        $validate = new $validate();
                        if ($this->modelSceneValidate) $validate->scene('add');
                        $validate->check($data);
                    }
                }

                if (is_array($data['fields'])) {
                    $data['data_fields'] = [];
                    foreach ($data['fields'] as $field) {
                        $data['data_fields'][$field['name']] = $field['value'];
                    }
                }

                $result = $this->model->save($data);
                $this->model->commit();
            } catch (Throwable $e) {
                $this->model->rollback();
             return $this->error($e->getMessage());
            }
            if ($result !== false) {
             return $this->success(__('Added successfully'));
            } else {
             return $this->error(__('No rows were added'));
            }
        }

        // 放在add方法内，就不需要额外添加权限节点了
     return $this->success('', [
            'controllers' => $this->getControllerList(),
        ]);
    }

    /**
     * 编辑重写
     * @throws Throwable
     */
    public function edit(): Response
    {
        $pk  = $this->model->getPk();
        $id  = $this->request->input($pk);
        $row = $this->model->find($id);
        if (!$row) {
         return $this->error(__('Record not found'));
        }

        if ($this->request->isPost()) {
            $data = $this->request->post();
            if (!$data) {
             return $this->error(__('Parameter %s can not be empty', ['']));
            }

            $data                  = $this->excludeFields($data);
            $data['controller_as'] = str_ireplace('.php', '', $data['controller'] ?? '');
            $data['controller_as'] = strtolower(str_ireplace(['\\', '.'], '/', $data['controller_as']));

            $result = false;
            $this->model->startTrans();
            try {
                // 模型验证
                if ($this->modelValidate) {
                    $validate = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                    if (class_exists($validate)) {
                        $validate = new $validate();
                        if ($this->modelSceneValidate) $validate->scene('edit');
                        $validate->check($data);
                    }
                }

                if (is_array($data['fields'])) {
                    $data['data_fields'] = [];
                    foreach ($data['fields'] as $field) {
                        $data['data_fields'][$field['name']] = $field['value'];
                    }
                }

                $result = $row->save($data);
                $this->model->commit();
            } catch (Throwable $e) {
                $this->model->rollback();
             return $this->error($e->getMessage());
            }
            if ($result !== false) {
             return $this->success(__('Update successful'));
            } else {
             return $this->error(__('No rows updated'));
            }
        }

     return $this->success('', [
            'row'         => $row,
            'controllers' => $this->getControllerList(),
        ]);
    }

    protected function getControllerList(): array
    {
        $outExcludeController = [
            'Addon.php',
            'Ajax.php',
            'Dashboard.php',
            'Index.php',
            'Module.php',
            'Terminal.php',
            'auth/AdminLog.php',
            'routine/AdminInfo.php',
            'routine/Config.php',
            'user/MoneyLog.php',
            'user/ScoreLog.php',
        ];
        $outControllers       = [];
        $controllers          = get_controller_list();
        foreach ($controllers as $key => $controller) {
            if (!in_array($controller, $outExcludeController)) {
                $outControllers[$key] = $controller;
            }
        }
        return $outControllers;
    }
}