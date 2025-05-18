<?php

use plugin\radmin\support\orm\Rdb;

class YourMigrationName
{
    /**
     * 执行迁移
     */
    public function up()
    {
        // 使用事务确保操作原子性

        $this->table_name1();
        $this->table_name2();
    }

    /**
     * 回滚迁移
     */
    public function down()
    {
        Rdb::startTrans();
        try {
            // 回滚操作应该与up()中的操作相反
            // 删除表示例
            /*
            Rdb::execute("DROP TABLE IF EXISTS `example_table`");
            */
            
            // 删除字段示例
            /*
            if (Rdb::getColumnInfo('example_table', 'new_column')) {
                Rdb::execute("
                    ALTER TABLE `example_table` 
                    DROP COLUMN `new_column`,
                    DROP INDEX `idx_new_column`;
                ");
            }
            */
            
            // 恢复字段修改示例
            /*
            Rdb::execute("
                ALTER TABLE `example_table` 
                MODIFY COLUMN `name` varchar(100) NOT NULL COMMENT '名称';
            ");
            */
            
            // 删除索引示例
            /*
            if (Rdb::getIndexInfo('example_table', 'idx_name_status')) {
                Rdb::execute("
                    ALTER TABLE `example_table` 
                    DROP INDEX `idx_name_status`;
                ");
            }
            */
            
            Rdb::commit();
        } catch (\Exception $e) {
            Rdb::rollback();
            throw $e;
        }
    }
    
    /**
     * 获取表字段信息
     */
    private static function getColumnInfo(string $table, string $column): ?array
    {
        $columns = Rdb::query("SHOW COLUMNS FROM `{$table}` LIKE ?", [$column]);
        return $columns[0] ?? null;
    }
    
    /**
     * 获取表索引信息
     */
    private static function getIndexInfo(string $table, string $index): ?array
    {
        $indexes = Rdb::query("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$index]);
        return $indexes[0] ?? null;
    }

    public function table_name1()
    {
        // 使用事务确保操作原子性
        Rdb::startTrans();
        try {
            // 创建表示例
            /*
            Rdb::execute("
                CREATE TABLE IF NOT EXISTS `example_table` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `name` varchar(100) NOT NULL COMMENT '名称',
                    `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态',
                    `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
                    `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
                    PRIMARY KEY (`id`),
                    KEY `idx_status` (`status`),
                    KEY `idx_created_at` (`created_at`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='示例表';
            ");
            */

            // 添加字段示例
            /*
            if (!Rdb::getColumnInfo('example_table', 'new_column')) {
                Rdb::execute("
                    ALTER TABLE `example_table`
                    ADD COLUMN `new_column` varchar(255) NULL COMMENT '新字段' AFTER `name`,
                    ADD INDEX `idx_new_column` (`new_column`);
                ");
            }
            */

            // 修改字段示例
            /*
            Rdb::execute("
                ALTER TABLE `example_table`
                MODIFY COLUMN `name` varchar(150) NOT NULL COMMENT '修改后的名称';
            ");
            */

            // 创建索引示例
            /*
            if (!Rdb::getIndexInfo('example_table', 'idx_name_status')) {
                Rdb::execute("
                    ALTER TABLE `example_table`
                    ADD INDEX `idx_name_status` (`name`, `status`);
                ");
            }
            */
            // todo 插入数据
            // todo 修改数据
            // todo 删除数据

            Rdb::commit();
        } catch (\Exception $e) {
            Rdb::rollback();
            throw $e;
        }
    }
    public function table_name2()
    {
        // 使用事务确保操作原子性
        Rdb::startTrans();
        try {
            // 创建表示例
            /*
            Rdb::execute("
                CREATE TABLE IF NOT EXISTS `example_table` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `name` varchar(100) NOT NULL COMMENT '名称',
                    `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态',
                    `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
                    `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
                    PRIMARY KEY (`id`),
                    KEY `idx_status` (`status`),
                    KEY `idx_created_at` (`created_at`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='示例表';
            ");
            */

            // 添加字段示例
            /*
            if (!Rdb::getColumnInfo('example_table', 'new_column')) {
                Rdb::execute("
                    ALTER TABLE `example_table`
                    ADD COLUMN `new_column` varchar(255) NULL COMMENT '新字段' AFTER `name`,
                    ADD INDEX `idx_new_column` (`new_column`);
                ");
            }
            */

            // 修改字段示例
            /*
            Rdb::execute("
                ALTER TABLE `example_table`
                MODIFY COLUMN `name` varchar(150) NOT NULL COMMENT '修改后的名称';
            ");
            */

            // 创建索引示例
            /*
            if (!Rdb::getIndexInfo('example_table', 'idx_name_status')) {
                Rdb::execute("
                    ALTER TABLE `example_table`
                    ADD INDEX `idx_name_status` (`name`, `status`);
                ");
            }
            */

            Rdb::commit();
        } catch (\Exception $e) {
            Rdb::rollback();
            throw $e;
        }
    }
}