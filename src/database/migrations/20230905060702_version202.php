<?php

use Phinx\Migration\AbstractMigration;
use Radmin\orm\Rdb;

class Version202 extends AbstractMigration
{
    /**
     * 规范菜单规则
     * @throws Throwable
     */
    public function up()
    {
        Rdb::startTrans();
        try {
            $dashboardId = Rdb::name('admin_rule')
                ->where('name', 'dashboard/dashboard')
                ->lock(true)
                ->value('id');
            if ($dashboardId) {
                // 修改name
                Rdb::name('admin_rule')
                    ->where('name', 'dashboard/dashboard')
                    ->update([
                        'name' => 'dashboard',
                    ]);

                // 增加一个查看的权限节点
                $dashboardIndexId = Rdb::name('admin_rule')->insertGetId([
                    'pid'         => $dashboardId,
                    'type'        => 'button',
                    'title'       => '查看',
                    'name'        => 'dashboard/index',
                    'update_time' => time(),
                    'create_time' => time(),
                ]);

                // 原本有控制台权限的管理员，给予新增的查看权限
                $group = Rdb::name('admin_group')
                    ->where('rules', 'find in set', $dashboardId)
                    ->select();
                foreach ($group as $item) {

                    $newRules = trim($item['rules'], ',');
                    $newRules = $newRules . ',' . $dashboardIndexId;

                    Rdb::name('admin_group')
                        ->where('id', $item['id'])
                        ->update([
                            'rules' => $newRules
                        ]);
                }
            }

            // 修改name
            Rdb::name('admin_rule')
                ->where('name', 'buildadmin/buildadmin')
                ->update([
                    'name' => 'buildadmin',
                ]);

            Rdb::commit();
        } catch (Throwable $e) {
            Rdb::rollback();
            throw $e;
        }
    }
}
