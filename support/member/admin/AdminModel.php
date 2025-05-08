<?php
/**
 * File      AdminModel.php
 * Author    albert@rocareer.com
 * Time      2025-05-06 06:21:36
 * Describe  AdminModel.php
 */

namespace support\member\admin;

use plugin\radmin\support\member\Model;

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


}