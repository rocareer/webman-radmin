<?php
/**
 * File:        20250516000000_radmin105.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/16 00:00
 * Description: v105 数据升级 - 添加菜单规则
 *
 * Copyright [2014-2026] [https://rocareer.com]
 * Licensed under the Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */

use Phinx\Migration\AbstractMigration;
use Radmin\Log;

class Radmin105 extends AbstractMigration
{
    /**
     * @throws Throwable
     */
    public function up()
    {
        $this->insertAdminRules();
    }

    /**
     * 插入管理员规则数据
     * @return void
     * @throws \Exception
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/16 00:00
     */
    public function insertAdminRules()
    {
        // 获取表对象
        $table = $this->table(getDbPrefix() . 'admin_rule');

        // 准备要插入的数据
        $data = [
            [
                'id'          => 132,
                'pid'         => 55,
                'type'        => 'menu_dir',
                'title'       => '数据库备份',
                'name'        => 'security/backup',
                'path'        => 'security/backup',
                'icon'        => 'fa fa-upload',
                'menu_type'   => null,
                'url'         => '',
                'component'   => '',
                'keepalive'   => 0,
                'extend'      => 'none',
                'remark'      => '',
                'weigh'       => 0,
                'status'      => '1',
                'update_time' => 1747048032,
                'create_time' => 1747047140
            ],
            [
                'id'          => 146,
                'pid'         => 0,
                'type'        => 'menu_dir',
                'title'       => '数据管理',
                'name'        => 'data',
                'path'        => '',
                'icon'        => 'fa fa-database',
                'menu_type'   => 'tab',
                'url'         => '',
                'component'   => '',
                'keepalive'   => 0,
                'extend'      => 'none',
                'remark'      => '',
                'weigh'       => 0,
                'status'      => '1',
                'update_time' => 1747050863,
                'create_time' => 1747050813
            ],
            [
                'id'          => 160,
                'pid'         => 146,
                'type'        => 'menu',
                'title'       => '数据备份',
                'name'        => 'data/backup',
                'path'        => 'data/backup',
                'icon'        => '',
                'menu_type'   => 'tab',
                'url'         => '',
                'component'   => '/src/views/backend/data/backup/index.vue',
                'keepalive'   => 1,
                'extend'      => 'none',
                'remark'      => '',
                'weigh'       => 0,
                'status'      => '1',
                'update_time' => 1747063292,
                'create_time' => 1747051055
            ],
            [
                'id'          => 161,
                'pid'         => 160,
                'type'        => 'button',
                'title'       => '查看',
                'name'        => 'data/backup/index',
                'path'        => '',
                'icon'        => '',
                'menu_type'   => null,
                'url'         => '',
                'component'   => '',
                'keepalive'   => 0,
                'extend'      => 'none',
                'remark'      => '',
                'weigh'       => 0,
                'status'      => '1',
                'update_time' => 1747063377,
                'create_time' => 1747051055
            ],
            [
                'id'          => 162,
                'pid'         => 160,
                'type'        => 'button',
                'title'       => '添加',
                'name'        => 'data/backup/add',
                'path'        => '',
                'icon'        => '',
                'menu_type'   => null,
                'url'         => '',
                'component'   => '',
                'keepalive'   => 0,
                'extend'      => 'none',
                'remark'      => '',
                'weigh'       => 0,
                'status'      => '1',
                'update_time' => 1747063359,
                'create_time' => 1747051055
            ],
            [
                'id'          => 163,
                'pid'         => 160,
                'type'        => 'button',
                'title'       => '编辑',
                'name'        => 'data/backup/edit',
                'path'        => '',
                'icon'        => '',
                'menu_type'   => null,
                'url'         => '',
                'component'   => '',
                'keepalive'   => 0,
                'extend'      => 'none',
                'remark'      => '',
                'weigh'       => 0,
                'status'      => '1',
                'update_time' => 1747063344,
                'create_time' => 1747051055
            ],
            [
                'id'          => 164,
                'pid'         => 160,
                'type'        => 'button',
                'title'       => '删除',
                'name'        => 'data/backup/del',
                'path'        => '',
                'icon'        => '',
                'menu_type'   => null,
                'url'         => '',
                'component'   => '',
                'keepalive'   => 0,
                'extend'      => 'none',
                'remark'      => '',
                'weigh'       => 0,
                'status'      => '1',
                'update_time' => 1747063328,
                'create_time' => 1747051055
            ],
            [
                'id'          => 165,
                'pid'         => 160,
                'type'        => 'button',
                'title'       => '快速排序',
                'name'        => 'data/backup/sortable',
                'path'        => '',
                'icon'        => '',
                'menu_type'   => null,
                'url'         => '',
                'component'   => '',
                'keepalive'   => 0,
                'extend'      => 'none',
                'remark'      => '',
                'weigh'       => 0,
                'status'      => '1',
                'update_time' => 1747063314,
                'create_time' => 1747051055
            ],
            [
                'id'          => 166,
                'pid'         => 0,
                'type'        => 'menu_dir',
                'title'       => '日志管理',
                'name'        => 'log',
                'path'        => 'log',
                'icon'        => 'el-icon-InfoFilled',
                'menu_type'   => null,
                'url'         => '',
                'component'   => '',
                'keepalive'   => 0,
                'extend'      => 'none',
                'remark'      => '',
                'weigh'       => 0,
                'status'      => '1',
                'update_time' => 1747051833,
                'create_time' => 1747051767
            ],
            [
                'id'          => 167,
                'pid'         => 166,
                'type'        => 'menu_dir',
                'title'       => 'login',
                'name'        => 'login',
                'path'        => 'login',
                'icon'        => '',
                'menu_type'   => null,
                'url'         => '',
                'component'   => '',
                'keepalive'   => 0,
                'extend'      => 'none',
                'remark'      => '',
                'weigh'       => 0,
                'status'      => '1',
                'update_time' => 1747051767,
                'create_time' => 1747051767
            ],
            [
                'id'          => 168,
                'pid'         => 167,
                'type'        => 'menu',
                'title'       => '管理员登录',
                'name'        => 'log/login/admin',
                'path'        => 'log/login/admin',
                'icon'        => '',
                'menu_type'   => 'tab',
                'url'         => '',
                'component'   => '/src/views/backend/log/login/admin/index.vue',
                'keepalive'   => 1,
                'extend'      => 'none',
                'remark'      => '',
                'weigh'       => 0,
                'status'      => '1',
                'update_time' => 1747051767,
                'create_time' => 1747051767
            ],
            [
                'id'          => 169,
                'pid'         => 168,
                'type'        => 'button',
                'title'       => '查看',
                'name'        => 'log/login/admin/index',
                'path'        => '',
                'icon'        => '',
                'menu_type'   => null,
                'url'         => '',
                'component'   => '',
                'keepalive'   => 0,
                'extend'      => 'none',
                'remark'      => '',
                'weigh'       => 0,
                'status'      => '1',
                'update_time' => 1747051767,
                'create_time' => 1747051767
            ],
            [
                'id'          => 170,
                'pid'         => 168,
                'type'        => 'button',
                'title'       => '添加',
                'name'        => 'log/login/admin/add',
                'path'        => '',
                'icon'        => '',
                'menu_type'   => null,
                'url'         => '',
                'component'   => '',
                'keepalive'   => 0,
                'extend'      => 'none',
                'remark'      => '',
                'weigh'       => 0,
                'status'      => '1',
                'update_time' => 1747051767,
                'create_time' => 1747051767
            ],
            [
                'id'          => 171,
                'pid'         => 168,
                'type'        => 'button',
                'title'       => '编辑',
                'name'        => 'log/login/admin/edit',
                'path'        => '',
                'icon'        => '',
                'menu_type'   => null,
                'url'         => '',
                'component'   => '',
                'keepalive'   => 0,
                'extend'      => 'none',
                'remark'      => '',
                'weigh'       => 0,
                'status'      => '1',
                'update_time' => 1747051767,
                'create_time' => 1747051767
            ],
            [
                'id'          => 172,
                'pid'         => 168,
                'type'        => 'button',
                'title'       => '删除',
                'name'        => 'log/login/admin/del',
                'path'        => '',
                'icon'        => '',
                'menu_type'   => null,
                'url'         => '',
                'component'   => '',
                'keepalive'   => 0,
                'extend'      => 'none',
                'remark'      => '',
                'weigh'       => 0,
                'status'      => '1',
                'update_time' => 1747051767,
                'create_time' => 1747051767
            ],
            [
                'id'          => 173,
                'pid'         => 168,
                'type'        => 'button',
                'title'       => '快速排序',
                'name'        => 'log/login/admin/sortable',
                'path'        => '',
                'icon'        => '',
                'menu_type'   => null,
                'url'         => '',
                'component'   => '',
                'keepalive'   => 0,
                'extend'      => 'none',
                'remark'      => '',
                'weigh'       => 0,
                'status'      => '1',
                'update_time' => 1747051767,
                'create_time' => 1747051767
            ],
            [
                'id'          => 175,
                'pid'         => 146,
                'type'        => 'menu',
                'title'       => '数据表',
                'name'        => 'data/table',
                'path'        => 'data/table',
                'icon'        => '',
                'menu_type'   => 'tab',
                'url'         => '',
                'component'   => '/src/views/backend/data/table/index.vue',
                'keepalive'   => 1,
                'extend'      => 'none',
                'remark'      => '',
                'weigh'       => 0,
                'status'      => '1',
                'update_time' => 1747062437,
                'create_time' => 1747053457
            ],
            [
                'id'          => 176,
                'pid'         => 175,
                'type'        => 'button',
                'title'       => '查看',
                'name'        => 'data/table/index',
                'path'        => '',
                'icon'        => '',
                'menu_type'   => null,
                'url'         => '',
                'component'   => '',
                'keepalive'   => 0,
                'extend'      => 'none',
                'remark'      => '',
                'weigh'       => 0,
                'status'      => '1',
                'update_time' => 1747062307,
                'create_time' => 1747053457
            ],
            [
                'id'          => 177,
                'pid'         => 175,
                'type'        => 'button',
                'title'       => '添加',
                'name'        => 'data/table/add',
                'path'        => '',
                'icon'        => '',
                'menu_type'   => null,
                'url'         => '',
                'component'   => '',
                'keepalive'   => 0,
                'extend'      => 'none',
                'remark'      => '',
                'weigh'       => 0,
                'status'      => '1',
                'update_time' => 1747062359,
                'create_time' => 1747053457
            ],
            [
                'id'          => 178,
                'pid'         => 175,
                'type'        => 'button',
                'title'       => '编辑',
                'name'        => 'data/table/edit',
                'path'        => '',
                'icon'        => '',
                'menu_type'   => null,
                'url'         => '',
                'component'   => '',
                'keepalive'   => 0,
                'extend'      => 'none',
                'remark'      => '',
                'weigh'       => 0,
                'status'      => '1',
                'update_time' => 1747062390,
                'create_time' => 1747053457
            ],
            [
                'id'          => 179,
                'pid'         => 175,
                'type'        => 'button',
                'title'       => '删除',
                'name'        => 'data/table/del',
                'path'        => '',
                'icon'        => '',
                'menu_type'   => null,
                'url'         => '',
                'component'   => '',
                'keepalive'   => 0,
                'extend'      => 'none',
                'remark'      => '',
                'weigh'       => 0,
                'status'      => '1',
                'update_time' => 1747062373,
                'create_time' => 1747053457
            ],
            [
                'id'          => 180,
                'pid'         => 175,
                'type'        => 'button',
                'title'       => '快速排序',
                'name'        => 'data/table/sortable',
                'path'        => '',
                'icon'        => '',
                'menu_type'   => null,
                'url'         => '',
                'component'   => '',
                'keepalive'   => 0,
                'extend'      => 'none',
                'remark'      => '',
                'weigh'       => 0,
                'status'      => '1',
                'update_time' => 1747062342,
                'create_time' => 1747053457
            ],
            [
                'id'          => 181,
                'pid'         => 175,
                'type'        => 'button',
                'title'       => '同步',
                'name'        => 'data/table/sync',
                'path'        => '',
                'icon'        => 'fa fa-circle-o',
                'menu_type'   => 'tab',
                'url'         => '',
                'component'   => '',
                'keepalive'   => 0,
                'extend'      => 'none',
                'remark'      => '',
                'weigh'       => 0,
                'status'      => '1',
                'update_time' => 1747062322,
                'create_time' => 1747061573
            ],
        ];

        try {
            // 检查每条记录是否已存在，如果不存在则插入
            foreach ($data as $item) {
                $id     = $item['id'];
                $exists = $this->fetchRow("SELECT id FROM ".getDbPrefix()."admin_rule WHERE id = $id");

                if (!$exists) {
                    $table->insert($item)->save();
                    Log::info('插入管理员规则', ['id' => $id, 'title' => $item['title']]);
                } else {
                    Log::info('管理员规则已存在', ['id' => $id, 'title' => $item['title']]);
                }
            }

            Log::info('管理员规则数据迁移完成');
        } catch (\Exception $e) {
            Log::error('管理员规则数据迁移失败', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
