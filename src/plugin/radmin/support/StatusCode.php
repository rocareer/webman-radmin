<?php


namespace support;

/**
 * 系统状态码定义
 */
class StatusCode
{
    const NEED_LOGIN                = 303;           // 未知错误
    const TOKEN_EXPIRED             = 409;           // 未知错误
    const NO_PERMISSION             = 401;
    const METHOD_NOT_ALLOWED = 433;
    const TOKEN_SHOULD_REFRESH      = 409;           // 未知错误
    const SYSTEM_ERROR              = 500;           // 未知错误
    const MEMBER_ERROR              = 666;           // 未知错误
    const TOKEN_INVALID             = 10002;           // 未知错误
    const USER_NOT_FOUND            = 10001;           // 未知错误
    const SERVER_ERROR              = 10004;           // 未知错误
    const USER_DISABLED             = 10008;           // 未知错误
    const LOGIN_ACCOUNT_LOCKED      = 10009;           // 未知错误
    const PASSWORD_ERROR            = 10010;           // 未知错误
    const PASSWORD_CHANGE_FAILED    = 10011;           // 未知错误
    const TOKEN_NOT_FOUND           = 10012;           // 未知错误
    const USER_SAVE_FAILED          = 10013;           // 未知错误
    const LOGIN_TYPE_NOTFOUND       = 10014;           // 未知错误
    const LOGIN_FAILED              = 10015;           // 未知错误
    const NOT_LOGIN                 = 10016;           // 未知错误
    const SESSION_MISMATCH          = 10017;           // 未知错误
    const VIP_EXPIRED               = 10018;           // 未知错误
    const USER_BANNED               = 10019;           // 未知错误
    const USERNAME_REQUIRED         = 10020;           // 未知错误
    const PASSWORD_REQUIRED         = 10021;           // 未知错误
    const CAPTCHA_REQUIRED          = 10022;           // 未知错误
    const TOKEN_CREATE_FAILED       = 10023;           // 未知错误
    const STATE_MANAGER_NOT_INIT    = 10024;           // 未知错误
    const STATE_MANAGER_CACHE_ERROR = 10025;           // 未知错误
    const UNAUTHORIZED              = 10027;           // 未知错误
    const AUTH_ERROR                = 10028;           // 未知错误
    const BUSINESS_ERROR            = 10029;           // 未知错误
    const VALIDATION_ERROR          = 10030;           // 未知错误
    const SAVE_CACHE_FAILED         = 10031;           // 未知错误
    const STATE_ERROR               = 10032;           // 未知错误
    const MEMBER_NOT_FOUND          = 10033;           // 未知错误
    const TOKEN_BLACK               = 10034;           // 未知错误
    const TOKEN_DECODE_FAILED       = 10035;           // 未知错误
    const TOKEN_ERROR               = 10036;           // 未知错误
    const TOKEN_ENCODE_FAILED       = 10037;           // 未知错误
    const TOKEN_VERIFY_FAILED       = 10038;           // 未知错误
    const TOKEN_REFRESH_FAILED      = 10039;           // 未知错误
    const TOKEN_DESTROY_FAILED      = 10040;           // 未知错误
    const AUTHENTICATOR_ERROR       = 10043;           // 未知错误
    const AUTHENTICATION_FAILED     = 10044;           // 未知错误
    const STATE_CACHE_FIND_FAILED   = 10045;           // 未知错误


    // 状态码消息定义
    const MESSAGES           = [
        self::NEED_LOGIN                => '未知错误',
        self::TOKEN_EXPIRED             => '未知错误',
        self::TOKEN_SHOULD_REFRESH      => '未知错误',
        self::SYSTEM_ERROR              => '未知错误',
        self::MEMBER_ERROR              => '未知错误',
        self::TOKEN_INVALID             => '未知错误',
        self::USER_NOT_FOUND            => '未知错误',
        self::SERVER_ERROR              => '未知错误',
        self::USER_DISABLED             => '未知错误',
        self::LOGIN_ACCOUNT_LOCKED      => '未知错误',
        self::PASSWORD_ERROR            => '未知错误',
        self::PASSWORD_CHANGE_FAILED    => '未知错误',
        self::TOKEN_NOT_FOUND           => '未知错误',
        self::USER_SAVE_FAILED          => '未知错误',
        self::LOGIN_TYPE_NOTFOUND       => '未知错误',
        self::LOGIN_FAILED              => '未知错误',
        self::NOT_LOGIN                 => '未知错误',
        self::SESSION_MISMATCH          => '未知错误',
        self::VIP_EXPIRED               => '未知错误',
        self::USER_BANNED               => '未知错误',
        self::USERNAME_REQUIRED         => '未知错误',
        self::PASSWORD_REQUIRED         => '未知错误',
        self::CAPTCHA_REQUIRED          => '未知错误',
        self::TOKEN_CREATE_FAILED       => '未知错误',
        self::STATE_MANAGER_NOT_INIT    => '未知错误',
        self::STATE_MANAGER_CACHE_ERROR => '未知错误',
        self::UNAUTHORIZED              => '未知错误',
        self::AUTH_ERROR                => '未知错误',
        self::BUSINESS_ERROR            => '未知错误',
        self::VALIDATION_ERROR          => '未知错误',
        self::SAVE_CACHE_FAILED         => '未知错误',
        self::STATE_ERROR               => '未知错误',
        self::MEMBER_NOT_FOUND          => '未知错误',
        self::TOKEN_BLACK               => '未知错误',
        self::TOKEN_DECODE_FAILED       => '未知错误',
        self::TOKEN_ERROR               => '未知错误',
        self::TOKEN_ENCODE_FAILED       => '未知错误',
        self::TOKEN_VERIFY_FAILED       => '未知错误',
        self::TOKEN_REFRESH_FAILED      => '未知错误',
        self::TOKEN_DESTROY_FAILED      => '未知错误',
        self::AUTHENTICATOR_ERROR       => '未知错误',
        self::AUTHENTICATION_FAILED     => '未知错误',
        self::STATE_CACHE_FIND_FAILED   => '未知错误',
    ];



    /**
     * 获取状态码对应的消息
     * @param int $code
     * @return string
     */
    public static function getMessage(int $code): string
    {
        return self::MESSAGES[$code] ?? '未知错误';
    }
}