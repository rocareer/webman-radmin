<?php


namespace support\member\user;

use support\member\Authenticator;
use exception\UnauthorizedHttpException;

class UserAuthenticator extends Authenticator
{
    protected string $role = 'user';

    /**
     * 认证用户
     * @param array $credentials 用户凭证
     * @return object
     * @throws UnauthorizedHttpException
     */
    public function authenticate(array $credentials): object
    {
        return parent::authenticate($credentials);
    }


}

