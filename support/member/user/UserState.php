<?php

namespace support\member\user;

use support\member\State;
use support\StatusCode;
use exception\AuthException;

class UserState extends State
{
    /**
     * 准备状态数据
     * @return array
     */
    protected function prepareStateData(): array
    {
        return [
            'user' => $this->user->getAllowedInfo(),
            'vip_level' => $this->user->vip_level ?? 0
        ];
    }

    /**
     * 检查扩展状态
     * @throws AuthException
     */
    protected function checkExtendStatus(): void
    {
        // 检查VIP状态
        if (isset($this->user->vip_level) && $this->user->vip_level > 0) {
            if (isset($this->user->vip_expire_time) && $this->user->vip_expire_time < time()) {
                throw new AuthException('VIP已过期', StatusCode::VIP_EXPIRED);
            }
        }

        // 检查封禁状态
        if (isset($this->user->is_banned) && $this->user->is_banned) {
            throw new AuthException('账号已被封禁', StatusCode::USER_BANNED);
        }

        // 基础状态检查
        if ($this->user->status !== 'enable') {
            throw new AuthException('账号已被禁用', StatusCode::USER_DISABLED);
        }
    }

    /**
     * @var string 登录日志表名
     */
    protected static string $loginLogTable = 'user_login_log';
}
