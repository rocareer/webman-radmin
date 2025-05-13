<?php


namespace support\member\admin;

use support\member\State;


class AdminState extends State
{
    public string $role = 'admin';


    /**
     * @var string 登录日志表名
     */
    protected static string $loginLogTable = 'admin_login_log';


    /**
     * 检查扩展状态
     */
    protected function checkExtendStatus(): void
    {
        // 简化状态检查
    }

}
