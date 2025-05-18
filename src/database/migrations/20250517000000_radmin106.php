<?php
/**
 * File:        20250517000000_radmin106.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/17 00:00
 * Description: v106 数据升级 - 创建数据管理相关表
 *
 * Copyright [2014-2026] [https://rocareer.com]
 * Licensed under the Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */

use Phinx\Migration\AbstractMigration;
use Radmin\Log;

class Radmin106 extends AbstractMigration
{
    /**
     * @throws Throwable
     */
    public function up()
    {
        $this->createDataBackupTable();
        $this->createDataBackupLogTable();
        $this->createDataTableTable();
    }

    /**
     * 创建数据备份表
     * @return void
     * @throws \Exception
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/17 00:00
     */
    public function createDataBackupTable()
    {
        $tableName = getDbPrefix() . 'data_backup';

        // 检查表是否已存在
        if ($this->hasTable($tableName)) {
            Log::info('表已存在，跳过创建', ['table' => $tableName]);
            return;
        }

        try {
            $table = $this->table($tableName, [
                'id'          => false,
                'primary_key' => ['id'],
                'engine'      => 'InnoDB',
                'encoding'    => 'utf8mb4',
                'collation'   => 'utf8mb4_unicode_ci',
                'comment'     => '数据备份',
                'row_format'  => 'DYNAMIC'
            ]);

            $table->addColumn('id', 'integer', [
                'signed'   => false,
                'identity' => true,
                'comment'  => 'ID'
            ])
                ->addColumn('table_name', 'string', [
                    'null'    => true,
                    'default' => '',
                    'limit'   => 255,
                    'comment' => '表名'
                ])
                ->addColumn('version', 'string', [
                    'null'    => true,
                    'default' => '',
                    'limit'   => 255,
                    'comment' => '版本号'
                ])
                ->addColumn('file', 'string', [
                    'null'    => false,
                    'default' => '',
                    'limit'   => 255,
                    'comment' => '备份文件'
                ])
                ->addColumn('remark', 'string', [
                    'null'    => false,
                    'default' => '',
                    'limit'   => 255,
                    'comment' => '备注'
                ])
                ->addColumn('status', 'integer', [
                    'signed'  => false,
                    'null'    => false,
                    'default' => 0,
                    'limit'   => 1,
                    'comment' => '状态:0=未开始,1=成功,2=失败'
                ])
                ->addColumn('runnow', 'integer', [
                    'signed'  => false,
                    'null'    => false,
                    'default' => 1,
                    'limit'   => 1,
                    'comment' => '立即开始:0=否,1=是'
                ])
                ->addColumn('run_time', 'integer', [
                    'signed'  => false,
                    'null'    => true,
                    'limit'   => 20,
                    'comment' => '执行时间'
                ])
                ->addColumn('create_time', 'integer', [
                    'signed'  => false,
                    'null'    => true,
                    'limit'   => 20,
                    'comment' => '创建时间'
                ])
                ->create();

            Log::info('创建数据备份表成功', ['table' => $tableName]);
        } catch (\Exception $e) {
            Log::error('创建数据备份表失败', ['table' => $tableName, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * 创建数据备份日志表
     * @return void
     * @throws \Exception
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/17 00:00
     */
    public function createDataBackupLogTable()
    {
        $tableName = getDbPrefix() . 'data_backup_log';

        // 检查表是否已存在
        if ($this->hasTable($tableName)) {
            Log::info('表已存在，跳过创建', ['table' => $tableName]);
            return;
        }

        try {
            $table = $this->table($tableName, [
                'id'          => false,
                'primary_key' => ['id'],
                'engine'      => 'InnoDB',
                'encoding'    => 'utf8mb4',
                'collation'   => 'utf8mb4_unicode_ci',
                'comment'     => '操作日志',
                'row_format'  => 'DYNAMIC'
            ]);

            $table->addColumn('id', 'integer', [
                'signed'   => false,
                'identity' => true,
                'comment'  => 'ID'
            ])
                ->addColumn('string', 'string', [
                    'null'    => false,
                    'default' => '',
                    'limit'   => 255,
                    'comment' => '字符串'
                ])
                ->addColumn('backup_id', 'integer', [
                    'signed'  => false,
                    'null'    => true,
                    'comment' => '表名'
                ])
                ->addColumn('create_time', 'integer', [
                    'signed'  => false,
                    'null'    => true,
                    'limit'   => 20,
                    'comment' => '创建时间'
                ])
                ->create();

            Log::info('创建数据备份日志表成功', ['table' => $tableName]);
        } catch (\Exception $e) {
            Log::error('创建数据备份日志表失败', ['table' => $tableName, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * 创建数据表信息表
     * @return void
     * @throws \Exception
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/17 00:00
     */
    public function createDataTableTable()
    {
        $tableName = getDbPrefix() . 'data_table';

        // 检查表是否已存在
        if ($this->hasTable($tableName)) {
            Log::info('表已存在，跳过创建', ['table' => $tableName]);
            return;
        }

        try {
            $table = $this->table($tableName, [
                'id'          => false,
                'primary_key' => ['id'],
                'engine'      => 'InnoDB',
                'encoding'    => 'utf8mb4',
                'collation'   => 'utf8mb4_unicode_ci',
                'comment'     => '数据表',
                'row_format'  => 'DYNAMIC'
            ]);

            $table->addColumn('id', 'integer', [
                'signed'   => false,
                'identity' => true,
                'comment'  => 'ID'
            ])
                ->addColumn('name', 'string', [
                    'null'    => false,
                    'default' => '',
                    'limit'   => 255,
                    'comment' => '表名'
                ])
                ->addColumn('charset', 'string', [
                    'null'    => false,
                    'default' => '',
                    'limit'   => 255,
                    'comment' => '字符集'
                ])
                ->addColumn('record_count', 'integer', [
                    'null'    => true,
                    'comment' => '记录数'
                ])
                ->addColumn('comment', 'string', [
                    'null'    => true,
                    'limit'   => 255,
                    'comment' => '描述'
                ])
                ->addColumn('engine', 'string', [
                    'null'    => false,
                    'default' => '',
                    'limit'   => 255,
                    'comment' => '引擎'
                ])
                ->addColumn('remark', 'string', [
                    'null'    => false,
                    'default' => '',
                    'limit'   => 255,
                    'comment' => '备注'
                ])
                ->addColumn('create_time', 'integer', [
                    'signed'  => false,
                    'null'    => true,
                    'limit'   => 20,
                    'comment' => '创建时间'
                ])
                ->create();

            Log::info('创建数据表信息表成功', ['table' => $tableName]);
        } catch (\Exception $e) {
            Log::error('创建数据表信息表失败', ['table' => $tableName, 'error' => $e->getMessage()]);
            throw $e;
        }
    }
}
