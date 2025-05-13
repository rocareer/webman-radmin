<?php

namespace app\admin\controller\user;

use plugin\radmin\extend\ba\Tree;
use plugin\radmin\support\Response;
use Throwable;
use app\admin\model\UserRule;
use app\admin\model\UserGroup;
use app\common\controller\Backend;

class Rule extends Backend
{
    /**
     * @var object
     * @phpstan-var UserRule
     */
    protected object $model;

    /**
     * @var Tree
     */
    protected Tree $tree;

    protected array $noNeedLogin = ['index'];

    protected string|array $preExcludeFields = ['create_time', 'update_time'];

    protected string|array $defaultSortField = ['weigh' => 'desc'];

    protected string|array $quickSearchField = 'title';

    /**
     * 远程select初始化传值
     * @var array
     */
    protected array $initValue;

    /**
     * 是否组装Tree
     * @var bool
     */
    protected bool $assembleTree;

    /**
     * 搜索关键词
     * @var string
     */
    protected string $keyword;

    public function initialize():void
    {
        parent::initialize();
        $this->model = new UserRule();
        $this->tree  = Tree::instance();

        $isTree          = $this->request->input('isTree', true);
        $this->initValue = $this->request->get("initValue/a", []);
        $this->initValue = array_filter($this->initValue);
        $this->keyword   = $this->request->input('quickSearch', '');

        // 有初始化值时不组装树状（初始化出来的值更好看）
        $this->assembleTree = $isTree && !$this->initValue;
    }

    public function index():Response
    {
        if ($this->request->input('select')) {
       return $this->select();
        }

     return $this->success('', [
            'list'   => $this->getRules(),
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

                if (!empty($data['pid'])) {
                    $groups = UserGroup::where('rules', '<>', '*')->select();
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
     * 远程下拉
     * @throws Throwable
     */
    public function select()
    {
        $data = $this->getRules([['status', '=', 1]]);

        if ($this->assembleTree) {
            $data = $this->tree->assembleTree($this->tree->getTreeArray($data, 'title'));
        }
     return $this->success('', [
            'options' => $data
        ]);
    }

    /**
     * 获取菜单规则
     * @throws Throwable
     */
    private function getRules(array $where = []): array
    {
        $pk      = $this->model->getPk();
        $initKey = $this->request->get("initKey/s", $pk);

        if ($this->keyword) {
            $keyword = explode(' ', $this->keyword);
            foreach ($keyword as $item) {
                $where[] = [$this->quickSearchField, 'like', '%' . $item . '%'];
            }
        }

        if ($this->initValue) {
            $where[] = [$initKey, 'in', $this->initValue];
        }

        $data = $this->model
            ->where($where)
            ->order($this->queryOrderBuilder())
            ->select()
            ->toArray();

        return $this->assembleTree ? $this->tree->assembleChild($data) : $data;
    }

}