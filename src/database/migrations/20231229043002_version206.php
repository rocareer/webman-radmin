<?php

use Phinx\Migration\AbstractMigration;
use Radmin\orm\Rdb;

class Version206 extends AbstractMigration
{
    /**
     * @throws Throwable
     */
    public function up()
    {
        $exist = Rdb::name('config')->where('name', 'backend_entrance')->value('id');
        if (!$exist) {
            $rows  = [
                [
                    'name'  => 'backend_entrance',
                    'group' => 'basics',
                    'title' => 'Backend entrance',
                    'type'  => 'string',
                    'value' => '/admin',
                    'rule'  => 'required',
                    'weigh' => 1,
                ],
            ];
            $table = $this->table(getDbPrefix().'config');
            $table->insert($rows)->saveData();
        }

        $crudLog = $this->table(getDbPrefix().'crud_log');
        if (!$crudLog->hasColumn('connection')) {
            $crudLog->addColumn('connection', 'string', ['limit' => 100, 'default' => '', 'comment' => '数据库连接配置标识', 'null' => false, 'after' => 'status']);
            $crudLog->save();
        }

        $securityDataRecycle = $this->table(getDbPrefix().'security_data_recycle');
        if (!$securityDataRecycle->hasColumn('connection')) {
            $securityDataRecycle->addColumn('connection', 'string', ['limit' => 100, 'default' => '', 'comment' => '数据库连接配置标识', 'null' => false, 'after' => 'data_table']);
            $securityDataRecycle->save();
        }

        $securityDataRecycleLog = $this->table(getDbPrefix().'security_data_recycle_log');
        if (!$securityDataRecycleLog->hasColumn('connection')) {
            $securityDataRecycleLog->addColumn('connection', 'string', ['limit' => 100, 'default' => '', 'comment' => '数据库连接配置标识', 'null' => false, 'after' => 'data_table']);
            $securityDataRecycleLog->save();
        }

        $securitySensitiveData = $this->table(getDbPrefix().'security_sensitive_data');
        if (!$securitySensitiveData->hasColumn('connection')) {
            $securitySensitiveData->addColumn('connection', 'string', ['limit' => 100, 'default' => '', 'comment' => '数据库连接配置标识', 'null' => false, 'after' => 'data_table']);
            $securitySensitiveData->save();
        }

        $securitySensitiveDataLog = $this->table(getDbPrefix().'security_sensitive_data_log');
        if (!$securitySensitiveDataLog->hasColumn('connection')) {
            $securitySensitiveDataLog->addColumn('connection', 'string', ['limit' => 100, 'default' => '', 'comment' => '数据库连接配置标识', 'null' => false, 'after' => 'data_table']);
            $securitySensitiveDataLog->save();
        }
    }
}
