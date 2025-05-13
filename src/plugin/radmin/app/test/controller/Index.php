<?php
/**
 * File      Index.php
 * Author    albert@rocareer.com
 * Time      2025-05-03 15:55:35
 * Describe  Index.php
 */

namespace plugin\radmin\app\test\controller;

use plugin\radmin\support\jwt\Jwt;

class Index
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
        return $data;
    }
}