<?php


namespace plugin\radmin\support\member;

/**
 * 状态管理器接口
 */
interface InterfaceModel
{
    /**
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
    public function verifyPassword(string $inputPassword, $member): bool;

    public function findById(int $id, bool $withAllowFields = true): object;


}