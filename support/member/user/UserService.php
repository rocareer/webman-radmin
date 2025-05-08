<?php


/**
 * File      UserService.php
 * Author    albert@rocareer.com
 * Time      2025-05-06 06:05:12
 * Describe  UserService.php
 */

namespace support\member\user;

use plugin\radmin\support\member\Service;

class UserService extends Service
{
    protected string $role = 'user';

    /**
     * 附加用户信息
     * By albert  2025/05/08 18:23:55
     * @return void
     */
    public function extendMemberInfo(): void
    {

    }

}