<?php
namespace support\member;

use exception\BusinessException;

class Container
{
    private static array $instances = [];

    /**
     * 获取实例
     * @param string $role 角色类型
     * @param string $type 类型 (service, model, state, authenticator)
     * @return object
     * @throws BusinessException
     */
    public static function get(string $role, string $type): object
    {
        $cacheKey = "{$type}_{$role}";

        if (!isset(self::$instances[$cacheKey])) {
            self::$instances[$cacheKey] = Factory::getInstance($role, $type);
        }

        return self::$instances[$cacheKey];
    }

    /**
     * 清除实例缓存
     * @param string|null $role 指定类型，为null时清除所有
     * @param string $type 类型 (service, model, state, authenticator)
     */
    public static function clear(?string $role = null, string $type = 'service'): void
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
