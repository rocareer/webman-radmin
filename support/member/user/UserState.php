<?php


namespace support\member\user;

use support\member\State;

class UserState extends State
{
    public string $role = 'user';


    /**
     * @var string 登录日志表名
     */
    protected static string $loginLogTable = 'login_log_user';


    /**
     * 检查扩展状态
     */
    protected function checkExtendStatus(): void
    {
        // 简化状态检查
    }

}
