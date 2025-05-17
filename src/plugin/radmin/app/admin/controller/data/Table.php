<?php

namespace plugin\radmin\app\admin\controller\data;

use plugin\radmin\app\common\controller\Backend;
use plugin\radmin\support\orm\Rdb;
use plugin\radmin\support\Response;
use Throwable;

/**
 * 数据管理
 */
class Table extends Backend
{
    /**
     * Table模型对象
     * @var object
     * @phpstan-var \plugin\radmin\app\admin\model\data\Table
     */
    protected object $model;

    protected array|string $preExcludeFields = ['id', 'record_count', 'create_time'];

    protected string|array $quickSearchField = ['name', 'comment'];

    public function initialize(): void
    {
        parent::initialize();
        $this->model = new \plugin\radmin\app\admin\model\data\Table();
    }


    public function index(): ?Response
    {

        list($where, $alias, $limit, $order) = $this->queryBuilder();

        $res = $this->model
            ->field($this->indexField)
            ->withJoin($this->withJoinTable, $this->withJoinType)
            ->alias($alias)
            ->where($where)
            ->order($order)
            ->paginate($limit);

        return $this->success('', [
            'list'   => $res->items(),
            'total'  => $res->total(),
            'remark' => get_route_remark(),
        ]);
    }

    /**
     * 同步数据表
     * @return Response
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/12 21:09
     */

    public function sync(): Response
    {
        try {
            $connection = Rdb::connect();
            $dbTables   = $connection->getTables();
            $sysTables  = $this->model->select()->toArray();

            Rdb::startTrans();
            try {
                // 从数据库同步
                foreach ($dbTables as $table) {
                    // 获取表信息
                    $createTable = $connection->query("SHOW CREATE TABLE `{$table}`")[0];

                    // 使用Table工具类获取表状态信息
                    $tableStatus = \plugin\radmin\support\orm\Table::status($table);

                    // 解析字符集
                    preg_match('/CHARSET=([^\s]+)/i', $createTable['Create Table'], $charsetMatch);
                    $charset = $charsetMatch[1] ?? 'unknown';

                    // 解析表注释
                    preg_match('/COMMENT=\'(.*?)\'/i', $createTable['Create Table'], $commentMatch);
                    $comment = $commentMatch[1] ?? '';

                    $table_record = $this->model->where('name', $table)->find();
                    if ($table_record) {
                        // 更新表记录
                        $table_record->update([
                            'id'           => $table_record->id,
                            'name'         => $table,
                            'charset'      => $charset,
                            'record_count' => $tableStatus['rows'], // 使用精确的行数统计
                            'engine'       => $tableStatus['engine'],
                            'comment'      => $comment,
                            'data_size'    => $tableStatus['dataSize'],    // 数据大小（字节）
                            'index_size'   => $tableStatus['indexSize'],   // 索引大小（字节）
                            'total_size'   => $tableStatus['size'],        // 总存储大小（字节）
                            'update_time'  => $tableStatus['lastModified'], // 使用表的最后修改时间
                            'create_time'  => $tableStatus['createTime']
                        ]);
                    } else {
                        $this->model->create([
                            'name'         => $table,
                            'table_type'   => 2,
                            'charset'      => $charset,
                            'record_count' => $tableStatus['rows'],
                            'engine'       => $tableStatus['engine'],
                            'comment'      => $comment,
                            'update_time'  => $tableStatus['lastModified'],
                            'create_time'  => $tableStatus['createTime']    // 使用表的创建时间
                        ]);
                    }
                }
                unset($table);

                // 检查数据库不存在的记录 删除管理系统中的记录
                foreach ($sysTables as $table) {
                    if (!in_array($table['name'], $dbTables)) {
                        $this->model->where('name', $table['name'])->delete();
                    }
                }

                Rdb::commit();
                return $this->success('同步成功', []);
            } catch (\Exception $e) {
                Rdb::rollback();
                return $this->error('同步失败');
            }
        } catch (\Exception $e) {
            Rdb::rollback();
            return $this->error('同步失败');
        }
    }


    /**
     * 若需重写查看、编辑、删除等方法，请复制 @see \plugin\radmin\app\admin\library\traits\Backend 中对应的方法至此进行重写
     */

    /**
     * 编辑
     * @throws Throwable
     */
    public function edit(): ?Response
    {
        $pk  = $this->model->getPk();
        $id  = $this->request->input($pk);
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

            $data = $this->excludeFields($data);

            $this->model->startTrans();
            try {
                // 模型验证
                if ($this->modelValidate) {
                    $validate = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                    if (class_exists($validate)) {
                        $validate = new $validate();
                        if ($this->modelSceneValidate) $validate->scene('edit');
                        $data[$pk] = $row[$pk];
                        $validate->check($data);
                    }
                }

                // 如果有注释字段，更新表描述
                if (!empty($data['comment'])) {
                    $tableUpdateResult = $this->updataTable($data);
                    if (!$tableUpdateResult) {
                        throw new \Exception(__('Failed to update table comment'));
                    }
                }

                // 保存记录到数据库
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
     * 更新数据表的描述信息
     * @param array $data 表数据，包含 name 和 comment
     * @return bool 是否更新成功
     */
    // 常量已移至 \plugin\radmin\support\orm\Table 类

    /**
     * 更新数据表的描述信息
     * @param array $data 表数据，包含 name 和 comment
     * @return bool 是否更新成功
     */
    protected function updataTable(array $data): bool
    {
        // 使用工具类的方法更新表注释
        return \plugin\radmin\support\orm\Table::updateTableComment($data['name'], $data['comment'] ?? '');
    }
}