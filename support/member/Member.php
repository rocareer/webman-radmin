<?php

namespace plugin\radmin\support\member;

use think\Facade;

/**
 * Member服务门面
 *
 * @package Rocareer\auth
 * @see     Service
 * @mixin Service
 *
 * @method static object initialization() 初始化
 * @method static mixed getRuleIds(?int $id = null)
 * @method static mixed hasRole(string $role, ?array $roles = null)
 * @method static array getAllAuthGroups(string $dataLimit, array $groupQueryWhere = [['status', '=', 1]])
 * @method static array getAdminChildGroups()
 * @method static void getGroupChildGroups(int $groupId, array &$children)
 * @method static array getGroupAdmins(array $groups)
 * @method static bool isSuperAdmin(?int $id=null)
 **/
class Member extends Facade
{
    private static ?string           $currentRole    = null; // 当前角色
    private static ?InterfaceService $currentService = null; // 当前服务实例

    /**
     * 设置当前角色
     * @param string $role 角色类型
     * @return $this
     */
    public static function setCurrentRole(string $role): self
    {
        self::$currentRole = $role;
        self::$currentService = Factory::getInstance($role, 'service'); // 更新当前服务实例
        return new self(); // 返回当前实例以支持链式调用
    }

    /**
     * 获取当前角色的服务实例
     * @return InterfaceService
     */
    private static function getCurrentService(): InterfaceService
    {
        // 如果当前角色未设置，则默认为 'admin'
        if (self::$currentRole === null) {
            self::$currentRole =request()->app??'admin';
            self::$currentService = Factory::getInstance(self::$currentRole, 'service'); // 更新当前服务实例
        }
        return self::$currentService;
    }


    /**
     * 获取门面类对应的服务类
     * @return string
     */
    protected static function getFacadeClass(): string
    {
        return InterfaceService::class; // 直接返回接口类
    }

    /**
     * 动态调用服务类方法
     * @param string $method 方法名
     * @param array $params 方法参数
     * @return mixed
     */
    public static function __callStatic($method, $params)
    {
        $memberService = self::getCurrentService(); // 获取当前角色的服务
        return $memberService->$method(...$params);
    }
}
