<?php
/**
 * File:        UserState.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/12 05:33
 * Description:
 *
 * Copyright [2014-2026] [https://rocareer.com]
 * Licensed under the Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */

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
