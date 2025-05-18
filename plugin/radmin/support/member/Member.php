<?php

/**
 * File      Member.php
 * Author    albert@rocareer.com
 * Time      2025-05-06 06:21:36
 * Describe  Member.php
 */

namespace plugin\radmin\support\member;
use Radmin\Container;
use think\Facade;

/**
 * 成员系统 Facade
 * @method static object initialization()                               // 初始化
 * @method static array login(array $credentials, bool $keep = false)   // 登录
 * @method static array register(array $credentials)                    // 注册
 * @method static array refreshToken(string $refreshToken)              // 刷新token
 * @method static bool logout()                                         // 退出登录
 * @method static bool hasRole($role, ?array $roles = null)             // 检查角色
 * @method static bool memberInitialization(string $token)              // 初始化
 */
class Member extends Facade
{
    /**
     * 设置当前角色 在非 Request 请求下使用
     * @param $role
     * @return   mixed
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/16 06:21
     */
    public static function setCurrentRole($role): mixed
    {
        $context = Container::get('member.context');
        $context->set('role',$role);
        return Container::get('member.service');
    }

    /**
     * @return   string
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/16 06:21
     */
    protected static function getFacadeClass(): string
    {
        return get_class(Container::get('member.service'));
    }
}

