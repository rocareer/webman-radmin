<?php
/**
 * File:        Cache.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/14 05:34
 * Description:
 *
 * Copyright [2014-2026] [https://rocareer.com]
 * Licensed under the Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */

namespace Radmin\token\driver;


use plugin\radmin\extend\ba\Random;
use plugin\radmin\support\StatusCode;
use plugin\radmin\support\token\driver\TokenExpirationException;
use Radmin\exception\TokenException;
use Radmin\token\TokenInterface;
use stdClass;
use support\Think\Cache as WebmanCache;
use Throwable;

class Cache implements TokenInterface
{

    protected string $story = 'token';

    public function __construct(protected array $config = [])
    {

        $this->expire_time      = $this->config['expire_time'] ?? $this->config['common_expire_time'];
        $this->refresh_keep_ttl = $this->config['refresh_keep_ttl'] ?? $this->config['common_refresh_keep_ttl'];
        $this->story            = $config['story'] ?? $this->story;
    }

    public function encode(array $payload = [], bool $keep = false): string
    {
        $payload['expire_time'] = (isset($payload['expire_time']) && $payload['expire_time'] > 0)
            ? (time() + $payload['expire_time'])
            : (time() + $this->expire_time);

        if ($keep) {
            $payload['expire_time'] = time() + $this->refresh_keep_ttl;
            $payload['type']        = $payload['type'] . '-refresh';
        }
        $payload['create_time'] = time();
        $token                  = Random::uuid();
        $payload['token']       = getEncryptedToken($token);

        $res = WebmanCache::store($this->story)->set($payload['token'], $payload, $payload['expire_time']);
        if (!$res) {
           throw new TokenException('token 缓存失败', StatusCode::TOKEN_CREATE_FAILED);
        }
        return $token;
    }

    /**
     * 解码
     * By albert  2025/05/06 20:49:35
     * @param string $token
     * @return bool|stdClass
     * @throws TokenException
     * @throws \Throwable
     */
    public function decode(string $token):stdClass
    {
        try {
            $token     = getEncryptedToken($token);
            $tokenData = WebmanCache::store($this->story)->get($token);
            if (!$tokenData) {
                throw new TokenException('Token解码失败', StatusCode::TOKEN_DECODE_FAILED);
            }
            return arrayToObject($tokenData);
        } catch (Throwable $e) {
            throw new TokenException($e->getMessage(), StatusCode::TOKEN_DECODE_FAILED);
        }
    }


    /**
     * 验证凭证
     * By albert  2025/05/06 17:17:11
     * @param string $token
     * @return bool|stdClass
     */
    public function Verify(string $token): bool|stdClass
    {
        try {
            $tokenData = $this->decode($token);
            if (!$tokenData) {
                throw new TokenException('凭证验证失败', StatusCode:: TOKEN_VERIFY_FAILED);
            }
            if ($this->expired($token, $tokenData)) {
                throw new TokenExpirationException('凭证需续期', StatusCode::TOKEN_SHOULD_REFRESH, ['type' => 'should refresh']);
            }
            return $tokenData;
        } catch (TokenExpirationException $e) {
            throw new TokenExpirationException('凭证需续期', StatusCode::TOKEN_SHOULD_REFRESH, ['type' => 'should refresh']);
        } catch (Throwable $e) {
            throw new TokenException($e->getMessage(), StatusCode::TOKEN_VERIFY_FAILED);
        }
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


    public function destroy(string $token): bool
    {

        try {
            $token = getEncryptedToken($token);
            return WebmanCache::store($this->story)->delete($token);
        } catch (Throwable $e) {
            throw new TokenException('Token销毁失败', StatusCode::TOKEN_DESTROY_FAILED);
        }
    }


    public function ttl(string $token, stdClass $tokenData = null): mixed
    {
        $tokenData = $tokenData ?? $this->decode($token);
        return $tokenData->expire_time - time();
    }


    /**
     */
    public function refresh(string $token): string
    {

        try {
            $payload               = $this->decode($token);
            $newPayload['user_id'] = $payload->user_id;
            $newPayload['type']    = str_replace('-refresh', '', $payload->type);
            $newToken              = $this->encode($newPayload);
            return $newToken;
        }  catch (Throwable $e) {
            throw new TokenException($e->getMessage(), StatusCode::TOKEN_REFRESH_FAILED);
        }
    }
}