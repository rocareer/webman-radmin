<?php
/**
 * File:        Token.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/11 21:26
 * Description:
 *
 * Copyright [2014-2026] [https://rocareer.com]
 * Licensed under the Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */

namespace Radmin\token;

use stdClass;
use think\Facade;

/**
 * Token门面类
 * @method static TokenInterface driver(string $name = null) 获取驱动实例
 * @method static stdClass verify(string $token)
 * @method static string encode(array $payload = [])
 * @method static stdClass decode(string $token)
 * @method static bool destroy(string $token)
 * @method static string refresh(string $token)
 * @method static bool shouldRefresh(string $token) 检查是否需要刷新
 * @method static string keep(array $payload) 设置keep
 * @method static int ttl(string $token, stdClass|null $payload) 获取token剩余时间
 * @method static bool expired(string $token, stdClass|null $payload = null) 获取token过期状态
 * @method static string getEncryptedToken()  获取加密token
 *
 */
class Token extends Facade
{
    /**
     * 获取门面类对应的服务类
     * @return   string
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/11 21:26
     */
    protected static function getFacadeClass(): string
    {
        return TokenManager::class;
    }

    // 这里可以重写父类的方法，以确保使用实例方法

    /**
     * __callStatic
     * @param $method
     * @param $params
     * @return   mixed
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/11 21:26
     */
    public static function __callStatic($method, $params)
    {
        // 每次调用时都创建一个新的 TokenManager 实例
        $tokenService = new TokenManager();
        return $tokenService->$method(...$params);
    }

}
