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

namespace Radmin\token\driver;

use plugin\radmin\extend\ba\Random;
use plugin\radmin\support\StatusCode;
use Radmin\exception\TokenException;
use Radmin\orm\Rdb;
use Radmin\token\TokenInterface;
use stdClass;
use Throwable;

class Mysql implements TokenInterface
{
    protected array  $config;
    protected string $table;
    protected string $token;
    protected array  $tokenData;
    protected int    $expire_time      = 3600;
    protected int    $refresh_keep_ttl = 600;


    public function __construct(array $config = [])
    {
        $this->config = $config;

        $this->expire_time      = $this->config['expire_time'] ?? $this->config['common_expire_time'];
        $this->refresh_keep_ttl = $this->config['refresh_keep_ttl'] ?? $this->config['common_refresh_keep_ttl'];
        $this->table            = $this->config['table'];

    }

    /**
     * 创建
     * By albert  2025/05/04 23:47:00
     * @param array $payload
     * @param bool  $keep
     * @return string
     * @throws TokenException
     */
    public function encode(array $payload = [], bool $keep = false): string
    {

      try {
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
            return Rdb::name($this->config['table'])->insert($payload) ? $token : '';
        } catch (Throwable $e) {
            throw new TokenException($e->getMessage(), StatusCode::TOKEN_CREATE_FAILED);
        }
    }


    /**
     * 验证
     * By albert  2025/05/04 23:55:01
     * @param string $token
     * @return stdClass
     * @throws TokenException
     */
    public function Verify(string $token): stdClass
    {
        try {
            $tokenData = $this->decode($token);
            if (!$tokenData) {
                throw new TokenException('凭证验证失败', StatusCode::TOKEN_VERIFY_FAILED);
            }
            if ($this->expired($token, $tokenData)) {
                throw new TokenException('凭证需续期', StatusCode::TOKEN_EXPIRED, ['type' => 'should refresh']);
            }
            return $tokenData;
        } catch (Throwable $e) {
            throw new TokenException($e->getMessage(), StatusCode::TOKEN_VERIFY_FAILED);
        }
    }

    /**
     * 过期
     * By albert  2025/05/04 23:55:12
     * @param string        $token
     * @param stdClass|null $tokenData
     * @return bool
     */
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

    /**
     * 获取
     * By albert  2025/05/04 23:50:52
     * @param string $token
     * @return stdClass
     * @throws TokenException
     * @protected int user_id
     * @protected int expire_time
     * @protected int create_time
     * @protected string type
     * @protected string token
     */
    public function decode(string $token):stdClass
    {
        try {
            $token     = getEncryptedToken($token);
            $tokenData = Rdb::name($this->config['table'])->where('token', $token)->find();
            if (!$tokenData) {
                throw new TokenException('凭证解码失败', StatusCode:: TOKEN_DECODE_FAILED);
            }
            return arrayToObject($tokenData);
        } catch (Throwable $e) {
            throw new TokenException($e->getMessage(), StatusCode::TOKEN_DECODE_FAILED);
        }
    }

    /**
     * 销毁
     * By albert  2025/05/03 09:43:09
     * @param string $token
     * @return bool
     * @throws TokenException
     */
    public function destroy(string $token): bool
    {
        try {
            $token = getEncryptedToken($token);
            return Rdb::name($this->config['table'])->where('token', $token)->delete() > 0;
        } catch (Throwable $e) {
            throw new TokenException('Token销毁失败', StatusCode::TOKEN_DESTROY_FAILED);
        }
    }

    /**
     * ttl
     * By albert  2025/05/04 23:56:54
     * @param string        $token
     * @param stdClass|null $tokenData
     * @return int|mixed
     */
    public function ttl(string $token, stdClass $tokenData = null): mixed
    {
        $tokenData = $tokenData ?? $this->decode($token);
        return $tokenData->expire_time - time();
    }


    /**
     * 续期
     * By albert  2025/05/06 21:05:28
     * @param string $token
     * @return string
     * @throws TokenException
     */
    public function refresh(string $token): string
    {

        try {
            $payload               = $this->decode($token);
            $newPayload['user_id'] = $payload->user_id;
            $newPayload['type']    = str_replace('-refresh', '', $payload->type);
            $newToken              = $this->encode($newPayload);
            return $newToken;
        } catch (Throwable $e) {
            throw new TokenException('Token续期失败', StatusCode::TOKEN_REFRESH_FAILED);
        }
    }
}
