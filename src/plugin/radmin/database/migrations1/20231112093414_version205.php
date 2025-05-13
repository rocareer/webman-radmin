<?php

use Phinx\Migration\AbstractMigration;
use app\admin\model\Config;
use plugin\radmin\support\think\Db;

class Version205 extends AbstractMigration
{
    public function up()
    {
        $configQuickEntrance = Config::where('name', 'config_quick_entrance')->find();
        $value               = $configQuickEntrance->value;
        foreach ($value as &$item) {
            if (str_starts_with($item['value'], '/admin/')) {
                $pathData = Db::name('admin_rule')->where('path', substr($item['value'], 7))->find();
                if ($pathData) {
                    $item['value'] = $pathData['name'];
                }
            }
        }
//        $configQuickEntrance->value = $value;

        Config::where('name', 'config_quick_entrance')->update(
            [
                'value' => $value,
            ]
        );
//        $configQuickEntrance->save();
    }
}
