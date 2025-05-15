<?php

use Phinx\Migration\AbstractMigration;
use plugin\radmin\support\think\orm\Rdb;
class Version201 extends AbstractMigration
{
    public function up()
    {
        $user = $this->table('ra_user');
        if ($user->hasIndex('email')) {
            $user->removeIndexByName('email')
                ->removeIndexByName('mobile')
                ->update();
        }
    }
}
