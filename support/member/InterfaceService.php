<?php

namespace plugin\radmin\support\member;

/**
 * 状态管理器接口
 */
interface InterfaceService
{

    public function getRuleIds(?int $id=null): array;

    public function hasRole($role, $roles = null): bool;

    public function extendMemberInfo():void;

}