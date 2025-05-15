<?php

/**
 * File      Member.php
 * Author    albert@rocareer.com
 * Time      2025-05-06 06:21:36
 * Describe  Member.php
 */

namespace plugin\radmin\support\member;
use support\Container;
use think\Facade;

/**
 * 成员系统 Facade
 *
 * @method static array login(array $credentials, bool $keep = false)
 * @method static array register(array $credentials)
 * @method static array refreshToken(string $refreshToken)
 * @method static bool logout()
 * @method static bool check()
 * @method static array|object getMember()
 * @method static bool memberInitialization(string $token)
 * @method static bool checkPermission(string $path, string $method = 'GET')
 * @method static array getPermissions()
 * @method static array getMenus()
 * @method static array getInfo()
 * @method static array getProfile()
 * @method static array updateProfile(array $data)
 * @method static array updatePassword(string $oldPassword, string $newPassword)
 * @method static array resetPassword(string $mobile, string $captcha, string $password)
 * @method static array sendCaptcha(string $mobile, string $event)
 * @method static array verifyCaptcha(string $mobile, string $captcha, string $event)
 * @method static array sendEmailCaptcha(string $email, string $event)
 * @method static array verifyEmailCaptcha(string $email, string $captcha, string $event)
 * @method static array bindMobile(string $mobile, string $captcha)
 * @method static array bindEmail(string $email, string $captcha)
 * @method static array unbindMobile()
 * @method static array unbindEmail()
 * @method static array getLoginLog(int $page = 1, int $limit = 10)
 * @method static array getOperationLog(int $page = 1, int $limit = 10)
 * @method static array getNotifications(int $page = 1, int $limit = 10)
 * @method static array readNotification(int $id)
 * @method static array readAllNotifications()
 * @method static array deleteNotification(int $id)
 * @method static array deleteAllNotifications()
 * @method static array getUnreadNotificationCount()
 */
class Member extends Facade
{
    protected static function getFacadeClass(): string
    {
        $context = Container::get('member.context');
        return get_class($context->get('service'));
    }
}

