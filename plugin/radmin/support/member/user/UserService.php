<?php


/**
 * File      UserService.php
 * Author    albert@rocareer.com
 * Time      2025-05-06 06:05:12
 * Describe  UserService.php
 */

namespace plugin\radmin\support\member\user;

use plugin\radmin\support\member\Service;

class UserService extends Service
{
    protected string $role = 'user';

    protected array $config=[
        'auth_group'        => 'user_group', // 用户组数据表名
        'auth_group_access' => '', // 用户-用户组关系表（关系字段）
        'auth_rule'         => 'user_rule', // 权限规则表
    ];
    /**
     * 附加用户信息
     * By albert  2025/05/08 18:23:55
     * @return void
     */
    public function extendMemberInfo(): void
    {
        parent::extendMemberInfo();
    }

}