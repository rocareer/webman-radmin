<?php

namespace app\admin\controller\auth;

use app\admin\model\AdminGroup;
use app\admin\model\AdminRule;
use app\common\controller\Backend;
use extend\ba\Tree;
use support\member\Member;
use Throwable;

class Rule extends Backend
{
    protected string|array $preExcludeFields = ['create_time', 'update_time'];

    protected string|array $defaultSortField = ['weigh' => 'desc'];

    protected string|array $quickSearchField = 'title';

    /**
     * @var object
     * @phpstan-var AdminRule
     */
    protected object $model;

    /**
     * @var Tree
     */
    protected Tree $tree;

    /**
     * 远程select初始化传值
     * @var array
     */
    protected array $initValue;

    /**
     * 搜索关键词
     * @var string
     */
    protected string $keyword;

    /**
     * 是否组装Tree
     * @var bool
     */
    protected bool $assembleTree;

    /**
     * 开启模型验证
     * @var bool
     */
    protected bool $modelValidate = false;

    public function initialize(): void
    {
        parent::initialize();
        $this->model = new AdminRule();
        $this->tree  = Tree::instance();

        $isTree          = $this->request->input('isTree', true);
        $this->initValue = $this->request->input('initValue', []);
        $this->initValue = array_filter($this->initValue);
        $this->keyword   = $this->request->input('quickSearch', '');

        // 有初始化值时不组装树状（初始化出来的值更好看）
        $this->assembleTree = $isTree && !$this->initValue;
    }

    public function index(): \support\Response
    {
        if ($this->request->input('select')) {
             $this->select();
        }

        return $this->success('', [
            'list'   => $this->getMenus(),
            'remark' => get_route_remark(),
        ]);
    }

    /**
     * 添加
     */
    public function add(): \support\Response
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if (!$data) {
                return $this->error(__('Parameter %s can not be empty', ['']));
            }

            $data = $this->excludeFields($data);
            if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                $data[$this->dataLimitField] = $this->request->member->id;
            }

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

                // 检查所有非超管的分组是否应该拥有此权限
                if (!empty($data['pid'])) {
                    $groups = AdminGroup::where('rules', '<>', '*')->select();
                    foreach ($groups as $group) {
                        $rules = explode(',', $group->rules);
                        if (in_array($data['pid'], $rules) && !in_array($this->model->id, $rules)) {
                            $rules[]      = $this->model->id;
                            $group->rules = implode(',', $rules);
                            $group->save();
                        }
                    }
                }

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

        return $this->error(__('Parameter error'));
    }

    /**
     * 编辑
     * @throws Throwable
     */
    public function edit(): \support\Response
    {
        $id  = $this->request->input($this->model->getPk());
        $row = $this->model->find($id);
        if (!$row) {
            return $this->error(__('Record not found'));
        }

        $dataLimitAdminIds = $this->getDataLimitAdminIds();
        if ($dataLimitAdminIds && !in_array($row[$this->dataLimitField], $dataLimitAdminIds)) {
            return $this->error(__('You have no permission'));
        }

        if ($this->request->isPost()) {
            $data = $this->request->post();
            if (!$data) {
                return $this->error(__('Parameter %s can not be empty', ['']));
            }

            $data   = $this->excludeFields($data);
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
                if (isset($data['pid']) && $data['pid'] > 0) {
                    // 满足意图并消除副作用
                    $parent = $this->model->where('id', $data['pid'])->find();
                    if ($parent['pid'] == $row['id']) {
                        $parent->pid = 0;
                        $parent->save();
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

    /**
     * 删除
     * @throws Throwable
     */
    public function del(): \support\Response
    {
        $ids = $this->request->input('ids', []);

        // 子级元素检查
        $subData = $this->model->where('pid', 'in', $ids)->column('pid', 'id');
        foreach ($subData as $key => $subDatum) {
            if (!in_array($key, $ids)) {
                return $this->error(__('Please delete the child element first, or use batch deletion'));
            }
        }

        return parent::del();
    }

    /**
     * 重写select方法
     * @throws Throwable
     */
    public function select()
    {
        $data = $this->getMenus([['type', 'in', ['menu_dir', 'menu']], ['status', '=', 1]]);

        if ($this->assembleTree) {
            $data = $this->tree->assembleTree($this->tree->getTreeArray($data, 'title'));
        }
        return $this->success('', [
            'options' => $data
        ]);
    }

    /**
     * 获取菜单列表
     * @throws Throwable
     */
    protected function getMenus($where = []): array
    {
        $pk      = $this->model->getPk();
        $initKey = $this->request->input("initKey", $pk);

        $ids = Member::getRuleIds($this->request->member->id);

        // 如果没有 * 则只获取用户拥有的规则
        if (!in_array('*', $ids)) {
            $where[] = ['id', 'in', $ids];
        }

        if ($this->keyword) {
            $keyword = explode(' ', $this->keyword);
            foreach ($keyword as $item) {
                $where[] = [$this->quickSearchField, 'like', '%' . $item . '%'];
            }
        }

        if ($this->initValue) {
            $where[] = [$initKey, 'in', $this->initValue];
        }

        // 读取用户组所有权限规则
        $rules = $this->model
            ->where($where)
            ->order($this->queryOrderBuilder())
            ->select()
            ->toArray();

        // 如果要求树状，此处先组装好 children
        return $this->assembleTree ? $this->tree->assembleChild($rules) : $rules;
    }
}