<?php
/**
 *
 *   +----------------------------------------------------------------------
 *   | Rocareer [ ROC YOUR CAREER ]
 *   +----------------------------------------------------------------------
 *   | Copyright (c) 2014~2025 Albert@rocareer.com All rights reserved.
 *   +----------------------------------------------------------------------
 *   | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 *   +----------------------------------------------------------------------
 *   | Author: albert <Albert@rocareer.com>
 *   +----------------------------------------------------------------------
 **/

namespace support\token\driver;

use support\jwt\Jwt as JwtFacade;
use support\StatusCode;
use support\token\TokenInterface;
use exception\TokenException;
use exception\TokenExpirationException;
use stdClass;

class Jwt implements TokenInterface
{
    protected array $config;
    protected $jwt;

    public function __construct(array $config = [])
    {
        $this->config = array_merge([
            'algo'        => 'HS256',
            'secret'      => getenv('JWT_SECRET_KEY', 'radmin_default_secret'),
            'public_key'  => env('JWT_PUBLIC_KEY'),
            'private_key' => env('JWT_PRIVATE_KEY'),
            'expire'      => 7200,
        ], $config);

        $this->expire_time      = $this->config['expire_time'] ?? $this->config['common_expire_time'];
        $this->refresh_keep_ttl = $this->config['refresh_keep_ttl'] ?? $this->config['common_refresh_keep_ttl'];
        $this->jwt=JwtFacade::getInstance($config);
    }

    public function encode(array $payload = [], bool $keep = false): string
    {

        $payload['expire_time'] = (isset($payload['expire_time']) && $payload['expire_time'] > 0)
            ? $payload['expire_time']
            : $this->expire_time;

        if ($keep) {
            $payload['expire_time'] = $this->refresh_keep_ttl;
            $payload['type']        = $payload['type'] . '-refresh';
        }
        $expire = $payload['expire_time'];
        unset($payload['expire_time']);
        unset($payload['create_time']);


        $token = $this->jwt->encode($payload, $this->getSigningKey(), $expire, $this->config['algo']);
        return $token;
    }

    public function decode(string $token): stdClass
    {
        $tokenData = $this->jwt->decode(
            $token,
            $this->getSigningKey(),
            $this->config['algo']
        );
        if (!$tokenData) {
           throw new TokenException('凭证解码失败', StatusCode::TOKEN_DECODE_FAILED);
        }
        $tokenData->expire_time = $tokenData->exp;
        return $tokenData;
    }

    /**
     * 验证
     * By albert  2025/05/03 09:41:19
     * @param string $token
     * @return bool|stdClass
     */
    public function Verify(string $token): stdClass
    {
        $tokenData = $this->jwt->Verify($token);

        if (!$tokenData || !$tokenData instanceof stdClass || $this->expired($token, $tokenData)) {
            throw new TokenException('凭证需续期', StatusCode::TOKEN_EXPIRED, ['type' => 'should refresh']);
        }
        $tokenData->expire_time = $tokenData->exp;
        return $tokenData;
    }

    public function expired(string $token, stdClass $tokenData = null): bool
    {
        $tokenData = $tokenData ?? $this->decode($token);
        if (!$tokenData) {
            return true;
        }
        if ($this->ttl($token, $tokenData) <= 0) {
            return true;
        }
        return false;
    }

    public function ttl(string $token, stdClass $tokenData = null): mixed
    {
        $tokenData = $tokenData ?? $this->decode($token);
        return $tokenData->exp - time();
    }

    /**
     * 销毁
     * By albert  2025/05/03 09:43:09
     * @param string $token
     * @return bool
     */
    public function destroy(string $token): bool
    {
        // JWT是无状态的，无法单独删除
        return $this->jwt->setBlacklist($token);
    }


    public function refresh(string $token): string
    {
        $payload               = $this->decode($token);
        $newPayload['user_id'] = $payload->user_id;
        $newPayload['type']    = str_replace('-refresh', '', $payload->type);
        $newToken              = $this->encode($newPayload);
        return $newToken;
    }


    protected function getSigningKey(): string
    {
        return in_array($this->config['algo'], ['RS256', 'RS384', 'RS512'])
            ? $this->config['private_key']
            : $this->config['secret'];
    }
}
