<?php

use Phinx\Migration\AbstractMigration;
use plugin\radmin\support\think\Db;
use Phinx\Db\Adapter\MysqlAdapter;

class Version200 extends AbstractMigration
{
    public function up()
    {
        $admin = $this->table('ra_admin');
        if ($admin->hasColumn('loginfailure')) {
            // 字段改名
            $admin->renameColumn('loginfailure', 'login_failure')
                ->renameColumn('lastlogintime', 'last_login_time')
                ->renameColumn('lastloginip', 'last_login_ip')
                ->renameColumn('updatetime', 'update_time')
                ->renameColumn('createtime', 'create_time')
                ->changeColumn('update_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '更新时间'])
                ->changeColumn('create_time', 'biginteger', ['after' => 'update_time', 'limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '创建时间'])
                ->save();
        }

        $adminGroup = $this->table('ra_admin_group');
        if ($adminGroup->hasColumn('updatetime')) {
            $adminGroup->renameColumn('updatetime', 'update_time')
                ->renameColumn('createtime', 'create_time')
                ->changeColumn('update_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '更新时间'])
                ->changeColumn('create_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '创建时间'])
                ->save();
        }

        $adminLog = $this->table('ra_admin_log');
        if ($adminLog->hasColumn('createtime')) {
            $adminLog->renameColumn('createtime', 'create_time')
                ->changeColumn('create_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '创建时间'])
                ->changeColumn('data', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'null' => true, 'default' => null, 'comment' => '请求数据'])
                ->save();
        }

        $attachment = $this->table('ra_attachment');
        if ($attachment->hasColumn('createtime')) {
            $attachment->renameColumn('createtime', 'create_time')
                ->renameColumn('lastuploadtime', 'last_upload_time')
                ->changeColumn('create_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '创建时间'])
                ->changeColumn('last_upload_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '最后上传时间'])
                ->save();
        }

        $captcha = $this->table('ra_captcha');
        if ($captcha->hasColumn('createtime')) {
            $captcha->renameColumn('createtime', 'create_time')
                ->renameColumn('expiretime', 'expire_time')
                ->changeColumn('create_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '创建时间'])
                ->changeColumn('expire_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '过期时间'])
                ->changeColumn('captcha', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR, 'null' => true, 'default' => null, 'comment' => '验证码数据'])
                ->save();
        }

        if ($this->hasTable('ra_menu_rule')) {
            $menuRule = $this->table('ra_menu_rule');
            if ($menuRule->hasColumn('updatetime') && $this->hasTable('ra_menu_rule')) {
                $menuRule->renameColumn('updatetime', 'update_time')
                    ->renameColumn('createtime', 'create_time')
                    ->changeColumn('update_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '更新时间'])
                    ->changeColumn('create_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '创建时间'])
                    ->save();
                $menuRule->rename('ra_admin_rule')->save();
                Db::name('admin_rule')
                    ->where('name', 'auth/menu')
                    ->update([
                        'name'      => 'auth/rule',
                        'path'      => 'auth/rule',
                        'component' => '/src/views/backend/auth/rule/index.vue',
                    ]);
                Db::name('admin_rule')->where('name', 'auth/menu/index')->update(['name' => 'auth/rule/index']);
                Db::name('admin_rule')->where('name', 'auth/menu/add')->update(['name' => 'auth/rule/add']);
                Db::name('admin_rule')->where('name', 'auth/menu/edit')->update(['name' => 'auth/rule/edit']);
                Db::name('admin_rule')->where('name', 'auth/menu/del')->update(['name' => 'auth/rule/del']);
                Db::name('admin_rule')->where('name', 'auth/menu/sortable')->update(['name' => 'auth/rule/sortable']);
                Db::name('admin_rule')->whereIn('name', [
                    'dashboard/dashboard',
                    'routine/attachment',
                ])->update(['remark' => 'Remark lang']);
            }
        }

        $securityDataRecycle = $this->table('ra_security_data_recycle');
        if ($securityDataRecycle->hasColumn('updatetime')) {
            $securityDataRecycle->renameColumn('updatetime', 'update_time')
                ->renameColumn('createtime', 'create_time')
                ->changeColumn('update_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '更新时间'])
                ->changeColumn('create_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '创建时间'])
                ->save();
        }

        $securityDataRecycleLog = $this->table('ra_security_data_recycle_log');
        if ($securityDataRecycleLog->hasColumn('createtime')) {
            $securityDataRecycleLog->renameColumn('createtime', 'create_time')
                ->changeColumn('create_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '创建时间'])
                ->save();
        }

        $securitySensitiveData = $this->table('ra_security_sensitive_data');
        if ($securitySensitiveData->hasColumn('updatetime')) {
            $securitySensitiveData->renameColumn('updatetime', 'update_time')
                ->renameColumn('createtime', 'create_time')
                ->changeColumn('update_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '更新时间'])
                ->changeColumn('create_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '创建时间'])
                ->save();
        }

        $securitySensitiveDataLog = $this->table('ra_security_sensitive_data_log');
        if ($securitySensitiveDataLog->hasColumn('createtime')) {
            $securitySensitiveDataLog->renameColumn('createtime', 'create_time')
                ->changeColumn('create_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '创建时间'])
                ->save();
        }

        $token = $this->table('ra_token');
        if ($token->hasColumn('createtime')) {
            $token->renameColumn('createtime', 'create_time')
                ->renameColumn('expiretime', 'expire_time')
                ->changeColumn('create_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '创建时间'])
                ->changeColumn('expire_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '过期时间'])
                ->save();
        }

        $userGroup = $this->table('ra_user_group');
        if ($userGroup->hasColumn('createtime')) {
            $userGroup->renameColumn('updatetime', 'update_time')
                ->renameColumn('createtime', 'create_time')
                ->changeColumn('update_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '更新时间'])
                ->changeColumn('create_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '创建时间'])
                ->save();
        }

        $userMoneyLog = $this->table('ra_user_money_log');
        if ($userMoneyLog->hasColumn('createtime')) {
            $userMoneyLog->renameColumn('createtime', 'create_time')
                ->changeColumn('create_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '创建时间'])
                ->save();
        }

        $userRule = $this->table('ra_user_rule');
        if ($userRule->hasColumn('createtime')) {
            $userRule->renameColumn('updatetime', 'update_time')
                ->renameColumn('createtime', 'create_time')
                ->changeColumn('update_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '更新时间'])
                ->changeColumn('create_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '创建时间'])
                ->changeColumn('type', 'enum', ['values' => 'route,menu_dir,menu,nav_user_menu,nav,button', 'default' => 'menu', 'comment' => '类型:route=路由,menu_dir=菜单目录,menu=菜单项,nav_user_menu=顶栏会员菜单下拉项,nav=顶栏菜单项,button=页面按钮', 'null' => false]);
            if (!$userRule->hasColumn('no_login_valid')) {
                $userRule->addColumn('no_login_valid', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_TINY, 'default' => 0, 'comment' => '未登录有效:0=否,1=是']);
            }
            $userRule->save();
        }

        $userScoreLog = $this->table('ra_user_score_log');
        if ($userScoreLog->hasColumn('createtime')) {
            $userScoreLog->renameColumn('createtime', 'create_time')
                ->changeColumn('create_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '创建时间'])
                ->save();
        }

        $user = $this->table('ra_user');
        if ($user->hasColumn('loginfailure')) {
            $user->renameColumn('lastlogintime', 'last_login_time')
                ->renameColumn('lastloginip', 'last_login_ip')
                ->renameColumn('loginfailure', 'login_failure')
                ->renameColumn('joinip', 'join_ip')
                ->renameColumn('jointime', 'join_time')
                ->renameColumn('updatetime', 'update_time')
                ->renameColumn('createtime', 'create_time')
                ->changeColumn('update_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '更新时间'])
                ->changeColumn('create_time', 'biginteger', ['after' => 'update_time', 'limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '创建时间'])
                ->save();
        }
    }
}
