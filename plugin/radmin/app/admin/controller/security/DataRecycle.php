<?php

namespace plugin\radmin\app\admin\controller\security;

use plugin\radmin\app\admin\model\DataRecycle as DataRecycleModel;
use plugin\radmin\app\common\controller\Backend;
use Radmin\Response;
use Throwable;

class DataRecycle extends Backend
{
    /**
     * @var object
     * @phpstan-var DataRecycleModel
     */
    protected object $model;

    // 排除字段
    protected string|array $preExcludeFields = ['update_time', 'create_time'];

    protected string|array $quickSearchField = 'name';

    public function initialize():void
    {
        parent::initialize();
        $this->model = new DataRecycleModel();
    }

    /**
     * 添加
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
     * 编辑
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
            'row' => $row
        ]);
    }

    protected function getControllerList(): array
    {
        $outExcludeController = [
            'Addon.php',
            'Ajax.php',
            'Module.php',
            'Terminal.php',
            'Dashboard.php',
            'Index.php',
            'routine/AdminInfo.php',
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