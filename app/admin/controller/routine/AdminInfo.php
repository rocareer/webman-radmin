<?php

namespace app\admin\controller\routine;

use app\admin\model\Admin;
use app\common\controller\Backend;
use plugin\radmin\service\auth\admin\AdminHelper;
use Throwable;

class AdminInfo extends Backend
{
    /**
     * @var object
     * @phpstan-var Admin
     */
    protected object $model;

    protected string|array $preExcludeFields = ['username', 'last_login_time', 'password', 'salt', 'status'];

    protected array $authAllowFields = ['id', 'username', 'nickname', 'avatar', 'email', 'mobile', 'motto', 'last_login_time'];

    public function initialize():void
    {
        parent::initialize();
        $this->model = AdminHelper::getAdmin();
    }

    public function index()
    {
        $info =AdminHelper::getInstance($this->request->member->id)->getAdminInfo();
        return $this->success('', [
            'info' => $info
        ]);
    }

    public function edit()
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

            if (!empty($data['avatar'])) {
                $row->avatar = $data['avatar'];
                if ($row->save()) {
                    return $this->success(__('Avatar modified successfully!'));
                }
            }

            // 数据验证
            if ($this->modelValidate) {
                try {
                    $validate = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                    $validate = new $validate();
                    $validate->scene('info')->check($data);
                } catch (Throwable $e) {
                    return $this->error($e->getMessage());
                }
            }

            if (!empty($data['password'])) {
                $this->model->resetPassword($this->request->member->id, $data['password']);
            }

            $data   = $this->excludeFields($data);
            $result = false;
            $this->model->startTrans();
            try {
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
    }
}