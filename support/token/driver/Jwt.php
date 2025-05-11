<?php
/**
 * File:        Jwt.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/11 11:36
 * Description: JWT 驱动实现类
 *
 * Copyright [2014-2026] [https://rocareer.com]
 * Licensed under the Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */

namespace support\token\driver;

use Exception;
use support\jwt\Jwt as JwtFacade;
use support\jwt\JwtService;
use support\StatusCode;
use support\token\Token;
use support\token\TokenInterface;
use exception\TokenException;
use stdClass;

class Jwt implements TokenInterface
{
    protected array      $config;
    protected JwtService $jwt;
    protected int        $expire_time = 7200;
    protected int        $keep_time   = 7200;

    public function __construct(array $config = [])
    {
        $this->config = $config;

        // 设置过期时间和保持时间
        $this->expire_time = $this->config['expire_time'] ?? $this->expire_time;
        $this->keep_time   = $this->config['keep_time'] ?? $this->keep_time;

        // 初始化 JWT 服务
        $this->jwt = JwtFacade::getInstance($config);
    }

    /**
     * 生成 JWT Token
     * @param array $payload
     * @return   string
     * @throws TokenException
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/11 21:30
     */
    public function encode(array $payload = []): string
    {
        // 默认载荷数据
        $payloadData = [
            'iss'   => $this->config['iss'],
            'sub'   => $payload['sub'] ?? '',
            'iat'   => time(),
            'jti'   => $payload['type'] ?? 'access' . '-' . Token::getEncryptedToken(),
            'exp'   => $payload['exp'] ?? $this->expire_time + time(),
            'roles' => $payload['roles'] ?? [],
            'role'  => $payload['role'] ?? '',
            'type'  => $payload['type'] ?? 'access',
        ];

        // 如果是刷新 Token，设置过期时间和类型
        if ($payload['keep'] ?? false) {
            $payloadData['exp']  = $this->keep_time + time();
            $payloadData['type'] = 'refresh';
        }

        // 生成 Token
        return $this->jwt->encode($payloadData, $this->config['jwt_secret'], $this->config['jwt_algo']);
    }

    /**
     * 解码 JWT Token
     *
     * @param string $token Token 字符串
     * @return stdClass
     * @throws Exception
     */
    public function decode(string $token): stdClass
    {
        return $this->jwt->decode($token, $this->config['jwt_secret'], $this->config['jwt_algo']);
    }

    /**
     * 验证 JWT Token
     *
     * @param string $token Token 字符串
     * @return stdClass
     * @throws TokenException
     */
    public function Verify(string $token): stdClass
    {
        return $this->jwt->Verify($token);
    }

    /**
     * 检查 Token 是否过期
     *
     * @param string        $token     Token 字符串
     * @param stdClass|null $tokenData 解码后的 Token 数据
     * @return bool
     * @throws Exception
     */
    public function expired(string $token, ?stdClass $tokenData = null): bool
    {
        $tokenData = $tokenData ?? $this->decode($token);
        return $this->ttl($token, $tokenData) <= 0;
    }

    /**
     * 获取 Token 的剩余有效时间
     *
     * @param string        $token     Token 字符串
     * @param stdClass|null $tokenData 解码后的 Token 数据
     * @return int
     * @throws Exception
     */
    public function ttl(string $token, ?stdClass $tokenData = null): int
    {
        $tokenData = $tokenData ?? $this->decode($token);
        return $tokenData->exp - time();
    }

    /**
     * 销毁 Token
     *
     * @param string $token Token 字符串
     * @return bool
     */
    public function destroy(string $token): bool
    {
        // JWT 是无状态的，无法单独删除，通常通过黑名单实现
        return $this->jwt->setBlacklist($token);
    }

    /**
     * 刷新 Token
     *
     * @param string $token 刷新凭证
     * @return string
     */
    public function refresh(string $token): string
    {
        return JwtFacade::refresh($token);
    }


    /**
     * 获取签名密钥
     *
     * @return string
     */
    protected function getSigningKey(): string
    {
        return in_array($this->config['algo'], ['RS256', 'RS384', 'RS512'])
            ? $this->config['private_key']
            : $this->config['secret'];
    }
}
