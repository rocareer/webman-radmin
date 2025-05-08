<?php


namespace support\member\admin;

use support\member\Authenticator;
use support\member\Member;
use exception\UnauthorizedHttpException;

class AdminAuthenticator extends Authenticator
{
    protected string $role = 'admin';


    // public function __construct()
    // {
    //     $this->memberModel=new AdminModel();
    //     parent:: __construct();
    // }


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
        if (Member::isSuperAdmin($this->memberModel->id)) {
            $this->memberModel->roles = ['super', 'admin'];
        }
    }


}

