<?php


namespace support\member\user;

use plugin\radmin\support\member\Authenticator;
use plugin\radmin\support\member\Member;
use Rocareer\Radmin\Exception\UnauthorizedHttpException;

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

    public function extendMemberInfo(): void
    {

    }


}

