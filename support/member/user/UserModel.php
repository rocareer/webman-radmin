<?php


/**
 * File      AdminModel.php
 * Author    albert@rocareer.com
 * Time      2025-05-06 06:21:36
 * Describe  AdminModel.php
 */

namespace support\member\user;

use app\admin\model\Admin;
use support\member\Model;
use think\db\exception\DbException;

class UserModel extends Model
{
    public string $role = 'user';

    /**
     * User模型
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

    protected $name = 'user';


    protected array $allowFields = ['id', 'username', 'nickname', 'email', 'mobile',
        'avatar', 'gender', 'birthday', 'money', 'score', 'join_time', 'motto', 'last_login_time', 'last_login_ip',
        'token','refresh_token','roles','test'];



    /**
     * @var string 自动写入时间戳
     * @noinspection PhpMissingFieldTypeInspection
     */
    protected $autoWriteTimestamp = true;



    public function __construct(array $config = [])
    {
        parent::__construct(array_merge([
            'auth_group'        => 'user_group', // 用户组数据表名
            'auth_group_access' => '', // 用户-用户组关系表（关系字段）
            'auth_rule'         => 'user_rule', // 权限规则表
        ], $config));

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