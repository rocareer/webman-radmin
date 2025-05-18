<?php


namespace plugin\radmin\support\member;

use Radmin\exception\BusinessException;

interface InterfaceAuthenticator
{

    /**
     * 认证用户凭证
     * @param array $credentials 用户凭证数据
     * @return mixed 认证结果
     * @throws BusinessException
     */
    public function authenticate(array $credentials): object;

    /**
     * 获取认证用户信息
     * @return void 用户信息
     * @throws BusinessException
     */
    public function extendMemberInfo(): void;


}
