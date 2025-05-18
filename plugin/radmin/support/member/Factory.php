<?php /** @noinspection PhpUnnecessaryCurlyVarSyntaxInspection */


namespace plugin\radmin\support\member;

use plugin\radmin\support\member\admin\AdminAuthenticator;
use plugin\radmin\support\member\admin\AdminModel;
use plugin\radmin\support\member\admin\AdminService;
use plugin\radmin\support\member\admin\AdminState;
use plugin\radmin\support\member\user\UserAuthenticator;
use plugin\radmin\support\member\user\UserModel;
use plugin\radmin\support\member\user\UserService;
use plugin\radmin\support\member\user\UserState;
use plugin\radmin\support\StatusCode;
use Radmin\exception\BusinessException;

class Factory
{
    private static array $instances = [];

    private static array $serviceClasses = [
        'admin' => AdminService::class,
        'user'  => UserService::class,
    ];

    private static array $modelClasses = [
        'admin' => AdminModel::class,
        'user'  => UserModel::class,
    ];

    private static array $stateManagerClasses = [
        'admin' => AdminState::class,
        'user'  => UserState::class,
    ];

    private static array $authenticatorClasses = [
        'admin' => AdminAuthenticator::class,
        'user'  => UserAuthenticator::class,
    ];

    /**
     * 获取实例
     * @param string $role 角色类型
     * @param string $type 类型 (service, model, state, authenticator)
     * @return object
     * @throws BusinessException
     */
    public static function getInstance(string $role, string $type): object
    {
        $classMap = [
            'service'       => self::$serviceClasses,
            'model'         => self::$modelClasses,
            'state'         => self::$stateManagerClasses,
            'authenticator' => self::$authenticatorClasses,
        ];

        if (!isset($classMap[$type])) {
            throw new BusinessException("未知的类型: {$type}");
        }

        if (!isset($classMap[$type][$role])) {
            throw new BusinessException("未知的 {$type} 类型: {$role}");
        }

        // 生成缓存键
        $cacheKey = "{$type}_{$role}";

        if (!isset(self::$instances[$cacheKey])) {
            $class    = $classMap[$type][$role];
            $instance = new $class();

            // 检查接口实现
            switch ($type) {
                case 'service':
                    if (!$instance instanceof InterfaceService) {
                        throw new BusinessException("服务类必须实现 InterfaceService 接口", StatusCode::SERVER_ERROR);
                    }
                    break;
                case 'model':
                    if (!$instance instanceof InterfaceModel) {
                        throw new BusinessException("模型类必须实现 InterfaceModel 接口", StatusCode::SERVER_ERROR);
                    }
                    break;
                case 'authenticator':
                    if (!$instance instanceof InterfaceAuthenticator) {
                        throw new BusinessException("服务类必须实现 InterfaceAuthenticator 接口", StatusCode::SERVER_ERROR);
                    }
                    break;
                case 'state':
                    if (!$instance instanceof InterfaceState) {
                        throw new BusinessException("模型类必须实现 StateManagerInterface 接口", StatusCode::SERVER_ERROR);
                    }
                    break;
            }

            self::$instances[$cacheKey] = $instance;
        }

        return self::$instances[$cacheKey];
    }

    /**
     * 注册自定义类
     * @param string $role  角色类型
     * @param string $class 类名
     * @param string $type  类型 (service, model, state, authenticator)
     * @throws BusinessException
     */
    public static function register(string $role, string $class, string $type): void
    {
        $classMap = [
            'service'       => &self::$serviceClasses,
            'model'         => &self::$modelClasses,
            'state'         => &self::$stateManagerClasses,
            'authenticator' => &self::$authenticatorClasses,
        ];

        if (!isset($classMap[$type])) {
            throw new BusinessException("未知的类型: {$type}");
        }

        if (!class_exists($class)) {
            throw new BusinessException("类不存在: {$class}");
        }

        // 检查接口实现
        switch ($type) {
            case 'service':
                if (!is_subclass_of($class, InterfaceService::class)) {
                    throw new BusinessException("服务类必须实现 InterfaceService 接口");
                }
                break;
            case 'model':
                if (!is_subclass_of($class, InterfaceModel::class)) {
                    throw new BusinessException("模型类必须实现 InterfaceModel 接口");
                }
                break;
            case 'state':
                if (!is_subclass_of($class, InterfaceState::class)) {
                    throw new BusinessException("服务类必须实现 StateManagerInterface 接口");
                }
                break;
            case 'authenticator':
                if (!is_subclass_of($class, InterfaceAuthenticator::class)) {
                    throw new BusinessException("模型类必须实现 InterfaceAuthenticator 接口");
                }
                break;
        }

        $classMap[$type][$role] = $class;
        // 清除已缓存的实例
        unset(self::$instances["{$type}_{$role}"]);
    }

    /**
     * 获取所有已注册的服务类型
     * @return array 服务类型列表
     */
    public static function getServiceRoles(): array
    {
        return array_keys(self::$serviceClasses);
    }

    /**
     * 获取所有已注册的模型类型
     * @return array 模型类型列表
     */
    public static function getModelRoles(): array
    {
        return array_keys(self::$modelClasses);
    }

    /**
     * 获取所有已注册的状态管理器类型
     * @return array 状态管理器类型列表
     */
    public static function getStateManagerRoles(): array
    {
        return array_keys(self::$stateManagerClasses);
    }

    /**
     * 获取所有已注册的认证器类型
     * @return array 认证器类型列表
     */
    public static function getAuthenticatorRoles(): array
    {
        return array_keys(self::$authenticatorClasses);
    }

    /**
     * 清除实例缓存
     * @param string|null $role 指定类型，为null时清除所有
     * @param string      $type 类型 (service, model, state, authenticator)
     */
    public static function clearInstances(?string $role = null, string $type = 'service'): void
    {
        if ($role === null) {
            foreach (self::$instances as $key => $value) {
                if (str_starts_with($key, $type)) {
                    unset(self::$instances[$key]);
                }
            }
        } else {
            unset(self::$instances["{$type}_{$role}"]);
        }
    }
}
