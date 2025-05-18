<?php /** @noinspection PhpMultipleClassDeclarationsInspection */


namespace Radmin\jwt;

use stdClass;
use think\Facade;

/**
 * JWT门面类
 *
 * @method static stdClass Verify(string $token) 验证JWT Token
 * @method static string refresh(string $token) 刷新JWT Token
 * @method static string encode(array $payload, string $secret = null, string $algo = 'HS256') 生成JWT Token
 * @method static stdClass decode(string $token, string|null $secret = null, string|null $algo = null) 解析JWT Token
 * @method static int ttl(string $token) 获取Token剩余有效时间（秒）
 * @method static bool isExpired(string $token) 判断Token是否过期
 * @method static bool setBlacklist(string $token,string $type) 设置黑名单
 * @method static bool inBlacklist(string $token) 判断Token是否在黑名单中
 * @method static bool shouldRefresh(string $token) 判断Token是否需要刷新
 */
class Jwt extends Facade
{
    /**
     * 获取门面类对应的服务类
     * @return string
     */
    protected static function getFacadeClass(): string
    {
        return JwtService::class;
    }

    public static function getInstance($config): JwtService
    {
        $adminService = new JwtService();
        return $adminService->getInstance($config);
    }

    // 这里可以重写父类的方法，以确保使用实例方法
    public static function __callStatic($method, $params)
    {
        // 每次调用时都创建一个新的 JwtService 实例
        $service = new JwtService();
        return $service->$method(...$params);
    }
}
