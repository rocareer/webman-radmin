<?php

namespace support\token;

use stdClass;
use think\Facade;

/**
 * Token门面类
 * @method static TokenInterface driver(string $name = null) 获取驱动实例
 * @method static stdClass verify(string $token)
 * @method static string encode(array $payload = [],bool $keep=false)
 * @method static stdClass decode(string $token)
 * @method static bool destroy(string $token)
 * @method static string refresh(string $token)
 * @method static bool shouldRefresh(string $token) 检查是否需要刷新
 * @method static string keep(array $payload) 设置keep
 * @method static int ttl(string $token,stdClass|null $payload) 获取token剩余时间
 * @method static bool expired(string $token,stdClass|null $payload=null) 获取token过期状态
 *
 */
class Token extends Facade
{


    /**
     * 获取门面类对应的服务类
     * @return string
     */
    protected static function getFacadeClass(): string
    {
        return TokenManager::class;
    }

    // 这里可以重写父类的方法，以确保使用实例方法
    public static function __callStatic($method, $params)
    {
        // 每次调用时都创建一个新的 TokenManager 实例
        $tokenService = new TokenManager();
        return $tokenService->$method(...$params);
    }
}
