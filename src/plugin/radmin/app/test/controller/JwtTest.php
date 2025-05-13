<?php
/**
 * File      Index.php
 * Author    albert@rocareer.com
 * Time      2025-05-03 15:55:35
 * Describe  Index.php
 */

namespace app\test\controller;

use plugin\radmin\support\jwt\Jwt;

class JwtTest
{

    public function index(){
        return 'test';
    }
    public function encode()
    {
        $token = Jwt::encode(['uid' => 123456], '123456', 10);
        return $token;
    }

    public function decode()
    {
        $token = request()->get('token');
        $data = Jwt::decode($token, '123456');
        return json($data);
    }

    public function isExpired()
    {
        $token=request()->get('token');
        $isExpired=Jwt::isExpired($token);
        return $isExpired;
    }

    public function shouldRefresh($token)
    {
        return Jwt::shouldRefresh($token);
    }
    public function ttl($token): int
    {
        return Jwt::ttl($token);
    }

    public function setBlackList($token)
    {
        return Jwt::setBlacklist($token);
    }

    public function inBlackList($token)
    {
        return Jwt::inBlacklist($token);
    }

}