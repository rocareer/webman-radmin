<?php

/**
 * File:        JwtService.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/11 22:02
 * Description:
 *
 * Copyright [2014-2026] [https://rocareer.com]
 * Licensed under the Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */

namespace Radmin\jwt;

use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use plugin\radmin\support\StatusCode;
use Psr\SimpleCache\InvalidArgumentException;
use Radmin\cache\Cache;
use Radmin\exception\TokenException;
use Radmin\exception\TokenExpiredException;
use ReflectionException;
use stdClass;
use Throwable;

/**
 * JWT服务类
 */
class JwtService
{
    private string $jwtSecret;
    private string $jwtAlgo;

    protected array $config = [];

    public function __construct(?array $config = [])
    {
        $this->config     = $config;
        $this->jwtSecret = $config['jwt_secret'] ?? '';
        $this->jwtAlgo   = $config['jwt_algo'] ?? 'HS256';
        // JWT::$leeway = 5; // 允许5秒误差
    }

    public static function getInstance(?array $config = []): self
    {
        return new self($config);
    }

    /**
     * 编码JWT
     *
     * @param array       $payload
     * @param string|null $jwtSecret
     * @param string|null $jwtAlgo
     * @return string
     * @throws TokenException
     */
    public function encode(array $payload, ?string $jwtSecret = null, ?string $jwtAlgo = null): string
    {
        try {
            return JWT::encode($payload, $jwtSecret ?? $this->jwtSecret, $jwtAlgo ?? $this->jwtAlgo);
        } catch (Throwable $e) {
            throw new TokenException($e->getMessage(), StatusCode::TOKEN_ENCODE_FAILED);
        }
    }

    /**
     * 解码JWT
     *
     * @param string      $token
     * @param string|null $jwtSecret
     * @param string|null $jwtAlgo
     * @return stdClass
     * @throws TokenException|TokenExpiredException
     */
    public function decode(string $token, ?string $jwtSecret = null, ?string $jwtAlgo = null): stdClass
    {
        try {
            return JWT::decode($token, new Key($jwtSecret ?? $this->jwtSecret, $jwtAlgo ?? $this->jwtAlgo));
        } catch (ExpiredException $e) {
            throw new TokenExpiredException('Token已过期', [], $e);
        } catch (Throwable $e) {
            throw new TokenException($e->getMessage(), StatusCode::TOKEN_DECODE_FAILED, [], $e);
        }
    }

    /**
     * 验证Token
     *
     * @param string $token
     * @return stdClass
     * @throws TokenException|Exception
     * @throws InvalidArgumentException
     */
    public function verify(string $token): stdClass
    {
        $payload = $this->decode($token);
        if ($this->inBlacklist($payload->jti)) {
            throw new TokenException('凭证已被废止', StatusCode::TOKEN_BLACK);
        }
        return $payload;
    }

    /**
     * 刷新Token
     *
     * @param string $token
     * @return string
     * @throws TokenException|TokenExpiredException
     */
    public function refresh(string $token): string
    {
        $payload = $this->decode($token);
        if ($payload->type !== 'refresh') {
            throw new TokenException('Token 不能刷新', StatusCode::TOKEN_REFRESH_FAILED);
        }
        return $this->encode(['type' => 'access']);
    }

    /**
     * 获取Token剩余时间
     *
     * @param string $token
     * @return int
     * @throws Exception
     */
    public function ttl(string $token): int
    {
        $payload = $this->decode($token);
        return max(0, $payload->exp - time());
    }

    /**
     * 将Token加入黑名单
     *
     * @param string $token
     * @return bool
     * @throws Exception|InvalidArgumentException
     * @noinspection PhpDynamicAsStaticMethodCallInspection
     */
    public function setBlackList(string $token): bool
    {
        $payload = $this->decode($token);
        if ($this->inBlackList($payload->jti)) {
            return true;
        }

        $type = str_starts_with($payload->jti, 'refresh-') ? 'Black-refresh-' : 'Black-access-';
        Cache::tag($type)->set($payload->jti, 1);
        return true;
    }

    /**
     * 检查Token是否在黑名单中
     *
     * @param string $jti
     * @return bool
     * @throws InvalidArgumentException|ReflectionException
     * @noinspection PhpDynamicAsStaticMethodCallInspection
     */
    public function inBlackList(string $jti): bool
    {
        return Cache::has($jti);
    }
}
