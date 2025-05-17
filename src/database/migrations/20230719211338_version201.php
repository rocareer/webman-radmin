<?php

use Phinx\Migration\AbstractMigration;

class Version201 extends AbstractMigration
{
    public function up()
    {
        $user = $this->table(getDbPrefix().'user');
        if ($user->hasIndex('email')) {
            $user->removeIndexByName('email')
                ->removeIndexByName('mobile')
                ->update();
        }
    }
}
