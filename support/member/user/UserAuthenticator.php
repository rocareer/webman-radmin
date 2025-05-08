<?php

namespace support\member\user;

use app\admin\model\User;
use exception\AuthException;
use exception\BusinessException;
use support\member\Authenticator;

class UserAuthenticator extends Authenticator
{

    protected string $role ='user';
    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * 认证用户
     * @param array $credentials 用户凭证
     * @return array 认证结果
     * @throws AuthException|BusinessException
     */
    public function authenticate(array $credentials): array
    {
        return parent::authenticate($credentials);
    }


    /**
     * 扩展用户特有信息
     * @return array
     */
    protected function extendUserInfo(): array
    {
        return [];
    }


}

