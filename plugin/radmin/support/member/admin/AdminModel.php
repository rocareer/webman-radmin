<?php

/**
 * File      AdminModel.php
 * Author    albert@rocareer.com
 * Time      2025-05-06 06:21:36
 * Describe  AdminModel.php
 */

namespace plugin\radmin\support\member\admin;

use plugin\radmin\app\admin\model\AdminGroup;
use plugin\radmin\support\member\Model;
use plugin\radmin\support\orm\Rdb;
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
class AdminModel extends Model
{
    public string $role = 'admin';

    // 表名
    protected $name = 'admin';
    // 允许字段
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

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    // 追加属性
    protected array $append = [
        'group_arr',
        'group_name_arr',
    ];

    /**
     * 获取管理员所属用户组ID数组
     * @param mixed $value
     * @param array $row
     * @return array
     * @noinspection PhpUnusedParameterInspection
     */
    public function getGroupArrAttr($value, $row): array
    {
        return Rdb::name('admin_group_access')
            ->where('uid', $row['id'])
            ->column('group_id');
    }

    /**
     * 获取管理员所属用户组名称数组
     * @param mixed $value
     * @param array $row
     * @return array
     * @noinspection PhpUnusedParameterInspection
     */
    public function getGroupNameArrAttr($value, $row): array
    {
        $groupAccess = Rdb::name('admin_group_access')
            ->where('uid', $row['id'])
            ->column('group_id');
        return (new AdminGroup)->whereIn('id', $groupAccess)->column('name');
    }

    /**
     * 获取头像
     * @param string $value
     * @return string
     */
    public function getAvatarAttr($value): string
    {
        return full_url($value, false, config('plugin.radmin.buildadmin.default_avatar'));
    }

    /**
     * 设置头像
     * @param string $value
     * @return string
     */
    public function setAvatarAttr($value): string
    {
        return $value == full_url('', false, config('plugin.radmin.buildadmin.default_avatar')) ? '' : $value;
    }

    /**
     * 获取最后登录时间
     * @param int $value
     * @return string
     */
    public function getLastLoginTimeAttr($value): string
    {
        return $value ? date('Y-m-d H:i:s', $value) : '';
    }

    /**
     * 重置用户密码
     * @param int|string $uid         管理员ID
     * @param string     $newPassword 新密码
     * @return int
     * @throws DbException
     */
    public function resetPassword(int|string $uid, string $newPassword): int
    {
        return $this->where(['id' => $uid])->update(['password' => hash_password($newPassword), 'salt' => '']);
    }

    /**
     * 根据用户名查找用户
     * @param string $username 用户名
     * @return AdminModel|null
     */
    public static function getByUsername(string $username)
    {
        return self::where('username', $username)->find();
    }
    
    /**
     * 检查用户名是否存在
     * @param string $username 用户名
     * @param int $exceptId 排除的用户ID
     * @return bool
     */
    public static function checkUsernameExists(string $username, int $exceptId = 0): bool
    {
        $query = self::where('username', $username);
        if ($exceptId > 0) {
            $query->where('id', '<>', $exceptId);
        }
        return $query->count() > 0;
    }
}