<?php


/**
 * File      AdminModel.php
 * Author    albert@rocareer.com
 * Time      2025-05-06 06:21:36
 * Describe  AdminModel.php
 */

namespace plugin\radmin\support\member\admin;

use plugin\radmin\app\admin\model\Admin;
use plugin\radmin\app\admin\model\AdminGroup;
use plugin\radmin\support\member\Model;
use upport\think\Db;
use think\db\exception\DbException;

class AdminModel extends Model
{
    public string $role = 'admin';

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

    protected $name = 'admin';


    protected array $allowFields = [
        'id',
        'username', 'nickname',
        'avatar', 'email', 'mobile',
        'status',
        'last_login_time', 'last_login_ip', 'login_failure',
        'create_time', 'update_time',
        'token', 'refresh_token',
        'super',
        'roles'
    ];


    /**
     * @var mixed 自动写入时间戳
     */
    protected mixed $autoWriteTimestamp = true;

    /**
     * 追加属性
     */
    protected array $append = [
        'group_arr',
        'group_name_arr',
    ];

    /**
     * @param $value
     * @param $row
     * @return array
     * @noinspection PhpUnusedParameterInspection
     */
    public function getGroupArrAttr($value, $row): array
    {
        return Db::name('admin_group_access')
            ->where('uid', $row['id'])
            ->column('group_id');
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function getGroupNameArrAttr($value, $row): array
    {
        $groupAccess = Db::name('admin_group_access')
            ->where('uid', $row['id'])
            ->column('group_id');
        return (new AdminGroup)->whereIn('id', $groupAccess)->column('name');
    }

    public function getAvatarAttr($value): string
    {
        return full_url($value, false,  config('buildadmin.default_avatar'));
    }

    public function setAvatarAttr($value): string
    {
        return $value == full_url('', false,  config('buildadmin.default_avatar')) ? '' : $value;
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
     */
    public function resetPassword(int|string $uid, string $newPassword): int|Admin
    {
        return $this->where(['id' => $uid])->update(['password' => hash_password($newPassword), 'salt' => '']);
    }

}