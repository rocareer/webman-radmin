<?php

namespace plugin\radmin\app\admin\controller\data;

use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use Throwable;
use plugin\radmin\app\common\controller\Backend;
use plugin\radmin\support\Response;

/**
 * 数据备份
 */
class Backup extends Backend
{
    /**
     * Backup模型对象
     * @var object
     */
    protected object $model;

    protected array|string $preExcludeFields = ['id', 'file', 'status', 'run_time', 'create_time'];

    protected array $withJoinTable = ['data_table'];

    protected string|array $quickSearchField = ['table_name'];

    protected string $backupPath ='backup';

    public function initialize():void
    {
        parent::initialize();
        $this->model = new \plugin\radmin\app\admin\model\data\Backup();
        $this->backupPath=get_sys_config('backup_path');
    }

    /**
     * 查看
     * @throws Throwable
     */
    public function index():Response
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
        $res->visible(['tableNameTable' => ['name']]);

        return $this->success('', [
            'list'   => $res->items(),
            'total'  => $res->total(),
            'remark' => get_route_remark(),
        ]);
    }

    public function run($id)
    {
        $row=$this->model->find($id);
        if (!$row){
           return $this->error('记录丢失');
        }
        $tableName=$row->table_name;

        // $backup_type=$row->backup_type;

    }

    /**
     * 下载备份文件
     * @param $id
     * @return Response
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/13 03:56
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function download(): Response
    {
        $id=$this->request->get('id');
        $row=$this->model->find($id);
        return response()->download(runtime_path() .$row->file,$row->version.'.zip');
    }

    /**
     * 若需重写查看、编辑、删除等方法，请复制 @see \app\admin\library\traits\Backend 中对应的方法至此进行重写
     */
}