<?php

namespace support\jwt;

use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use support\StatusCode;
use Rocareer\Radmin\Exception\TokenException;
use stdClass;
use support\think\Cache;

/**
 * JWT服务类
 */
class JwtService
{
    private string $secret;
    private int    $expire;
    private string $algo;

    protected array $config = [];

    public function __construct(?array $config = [])
    {
        $this->config = $config;
        $this->secret = $config['secret'] ?? '';
        $this->expire = $config['expire'] ?? 3600;
        $this->algo   = $config['algo'] ?? 'HS256';
        // JWT::$leeway = 5; // 允许5秒误差
    }

    public static function getInstance(?array $config = []): self
    {
        return new self($config);
    }

    /**
     *
     * By albert  2025/05/03 16:57:32
     * @param array       $payload
     * @param string|null $secret
     * @param int|null    $expire
     * @param string|null $algo
     * @return string
     */
    public function encode(array $payload, ?string $secret = null, ?int $expire = null, ?string $algo = null): string
    {
        try {
            $payload['iat'] = time();
            $payload['exp'] = time() + ($expire ?? $this->expire);
            return JWT::encode($payload, $secret ?? $this->secret, $algo ?? $this->algo);
        } catch (Exception $e) {
            throw new TokenException('Token编码失败', StatusCode::TOKEN_ENCODE_FAILED);
        }
    }

    /**
     *  验证并解码JWT token
     * By albert  2025/05/03 16:57:10
     * @param string      $token
     * @param string|null $secret
     * @param string|null $algo
     * @return stdClass
     * @throws Exception
     */
    public function decode(string $token, ?string $secret = null, ?string $algo = null): stdClass
    {
        try {
            return JWT::decode($token, new Key($secret ?? $this->secret, $algo ?? $this->algo));
        } catch (ExpiredException $e) {
            throw new TokenException('Token已过期', StatusCode::TOKEN_EXPIRED);
        } catch (Exception $e) {
            throw new TokenException($e->getMessage(), StatusCode::TOKEN_DECODE_FAILED);
        }
    }

    /**
     * 验证token
     * By albert  2025/05/03 20:39:26
     * @param string $token
     * @return stdClass
     * @throws TokenException|Exception
     */
    public function verify(string $token): stdClass
    {
        if ($this->inBlacklist($token)) {
            throw new TokenException('Token已失效', StatusCode::TOKEN_BLACK);
        }
        $payload = $this->decode($token);
        if (!$payload instanceof stdClass) {
            throw new TokenException('Token验证失败', StatusCode::TOKEN_VERIFY_FAILED);
        }
        return $payload;
    }

    /**
     *
     * By albert  2025/05/03 16:57:21
     * @param string $token
     * @return string
     * @throws TokenException
     */
    public function refresh(string $token): string
    {
        try {
            $payload = (array)$this->decode($token);
            unset($payload['iat'], $payload['exp']);
            return $this->encode($payload);
        } catch (TokenException $e) {
            throw new TokenException($e->getMessage(), StatusCode::TOKEN_REFRESH_FAILED);
        }
    }

    /**
     * 获取token剩余有效期（秒）
     */
    public function ttl(string $token): int
    {
        $payload = $this->decode($token);
        return max(0, $payload->exp - time());
    }

    /**
     * 检查token是否过期
     */
    public function isExpired(string $token): bool
    {
        return $this->ttl($token) <= 0;
    }

    /**
     * 检查token是否即将过期
     */
    public function shouldRefresh(string $token, int $threshold = 7130): bool
    {
        if ($this->ttl($token) >= 0 && $this->ttl($token) <= $threshold) {
            return true;
        }
        return false;
    }

    /**
     * 设置token到黑名单
     */
    public function setBlackList(string $token, int $expire = 3600 * 24 * 30): bool
    {
        if ($this->inBlackList($token)) {
            return true;
        }
        Cache::set('Jwt:blacklist-' . $token, 1, $expire);
        return true;
    }

    /**
     * 检查token是否在黑名单中
     */
    public function inBlackList(string $token): bool
    {
        return Cache::has('Jwt:blacklist-' . $token);
    }
}
