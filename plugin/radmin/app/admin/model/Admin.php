<?php
/** @noinspection PhpUnusedParameterInspection */

/** @noinspection PhpUnusedParameterInspection */

namespace plugin\radmin\app\admin\model;


use plugin\radmin\app\common\model\BaseModel;
use Radmin\orm\Rdb;
use think\db\exception\DbException;

/**
 * Admin模型
 * @property int    $id              管理员ID
 * @property string $username        管理员用户名
 * @property string $nickname        管理员昵称
 * @property string $email           管理员邮箱
 * @property string $mobile          管理员手机号
 * @property string $last_login_ip   上次登录IP
 * @property string $last_login_time 上次登录时间
 * @property int    $login_failure   登录失败次数
 * @property string $password        密码密文
 * @property string $salt            密码盐（废弃待删）
 * @property string $status          状态:enable=启用,disable=禁用,...(string存储，可自定义其他)
 */
class Admin extends BaseModel
{
    /**
     * @var string 自动写入时间戳
     */
    protected $autoWriteTimestamp = true;

    /**
     * 追加属性
     */
    protected array $append = [
        'group_arr',
        'group_name_arr',
    ];

    public function getGroupArrAttr($value, $row): array
    {
        return Rdb::name('admin_group_access')
            ->where('uid', $row['id'])
            ->column('group_id');
    }

    public function getGroupNameArrAttr($value, $row): array
    {
        $groupAccess = Rdb::name('admin_group_access')
            ->where('uid', $row['id'])
            ->column('group_id');
        return AdminGroup::whereIn('id', $groupAccess)->column('name');
    }

    public function getAvatarAttr($value): string
    {
        return full_url($value, false,  config('plugin.radmin.buildadmin.default_avatar'));
    }

    public function setAvatarAttr($value): string
    {
        return $value == full_url('', false,  config('plugin.radmin.buildadmin.default_avatar')) ? '' : $value;
    }

    public function getLastLoginTimeAttr($value): string
    {
        return $value ? date('Y-m-d H:i:s', $value) : '';
    }

    /**
     * 重置用户密码
     * @param int|string $uid         管理员ID
     * @param string     $newPassword 新密码
     * @return int|Admin
     * @throws DbException
     * @throws DbException
     */
    public function resetPassword(int|string $uid, string $newPassword): int|Admin
    {
        return $this->where(['id' => $uid])->update(['password' => hash_password($newPassword), 'salt' => '']);
    }
}