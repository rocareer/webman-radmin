<?php


namespace support\member;

use Rocareer\Radmin\Exception\BusinessException;
use think\Facade;

/**
 * Member服务门面
 *
 * @package Rocareer\auth
 * @see     Service
 * @mixin Service
 *
 * @method static object initialization() 初始化
 * @method static mixed getMenus(?int $id = null) 获取菜单()
 * @method static bool isLogin() 是否登录
 * @method static mixed getRuleIds(?int $id = null)
 * @method static mixed hasRole(string $role, ?array $roles = null)
 * @method static array getAllAuthGroups(string $dataLimit, array $groupQueryWhere = [['status', '=', 1]])
 * @method static array getAdminChildGroups()
 * @method static void getGroupChildGroups(int $groupId, array &$children)
 * @method static array getGroupAdmins(array $groups)
 * @method static bool isSuperAdmin(?int $id = null)
 **/
class Member extends Facade
{
    private static ?string           $currentRole    = null; // 当前角色
    private static ?InterfaceService $currentService = null; // 当前服务实例

    /**
     * 设置当前角色
     * @param string|null $role 角色类型
     * @throws BusinessException
     */
    public static function setCurrentRole(string $role = null)
    {
        self::$currentRole    = $role ?? 'admin'; // 如果 $role 为 null，默认设置为 'admin'
        self::$currentService = Factory::getInstance(self::$currentRole, 'service'); // 更新当前服务实例
        return self::$currentService; // 返回当前服务实例 // 返回当前类本身以支持链式调用
    }

    /**
     * 获取当前角色的服务实例
     * @return InterfaceService
     * @throws BusinessException
     */
    private static function getCurrentService(): InterfaceService
    {
        // 如果当前角色未设置，则默认为 'admin'
        if (self::$currentRole === null) {
            self::$currentRole    = 'admin';
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
     * @param array  $params 方法参数
     * @return mixed
     */
    public static function __callStatic($method, $params)
    {
        $memberService = self::getCurrentService(); // 获取当前角色的服务
        return $memberService->$method(...$params);
    }
}
