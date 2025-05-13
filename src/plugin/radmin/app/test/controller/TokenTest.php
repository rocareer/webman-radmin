<?php
/**
 * File      Index.php
 * Author    albert@rocareer.com
 * Time      2025-05-03 15:55:35
 * Describe  Index.php
 */

namespace app\test\controller;

use plugin\radmin\support\token\Token;
use plugin\radmin\support\token\TokenManager;
use plugin\radmin\support\Cache;

class TokenTest
{

    protected $tokenManager;

    public function __construct()
    {
        $this->tokenManager=new TokenManager();
    }
    public function index(){

        $res=Cache::store('token')->get('d4fbb8dcb857595b0332fc3c306fce081d31cace');

    }


    public function getDriver()
    {
       return $this->tokenManager->defaultDriver;
    }
    public function create()
    {
        $token =Token::encode(['1=1']);
        return $token;
    }

    public function validate()
    {
        $token = request()->get('token');
        $data = Token::verify($token);
        return json($data);
    }

    public function get()
    {
        $token=request()->get('token');
        $isExpired=Token::decode($token);
        return json($isExpired);
    }

    public function destroy($token)
    {
        return Token::destroy($token);
    }
    public function refresh($token)
    {
        return Token::refresh($token);
    }

    public function decode($token)
    {
        return Token::decode($token);
    }

}