<?php
/**
 * File      CacheTest.php
 * Author    albert@rocareer.com
 * Time      2025-05-05 09:40:43
 * Describe  CacheTest.php
 */

namespace app\test\controller;



use plugin\radmin\support\token\Token;

class CacheTest
{

    protected function refresh($token)
    {
        return Token::refresh($token);
    }
}