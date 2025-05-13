<?php
/**
 * File      Index.php
 * Author    albert@rocareer.com
 * Time      2025-05-03 15:55:35
 * Describe  Index.php
 */

namespace plugin\radmin\app\test\controller;

use plugin\radmin\support\jwt\Jwt;
use plugin\radmin\support\member\Factory;

class AuthTest
{

    public function login()
    {
        $credentials=[
          'username' => 'admin',
          'password' => '222222',
            'keep'=>true
        ];

        // $admin=new Admin();
        // $admin=$admin->find(1);
        // $admin->login_failure=33;
        // $admin->last_login_time=time();
        // $admin->save();
        $res=\service\auth\Member::login($credentials, 'admin',true);
        return json($res);
    }
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

    public function get2()
    {
        $service=Factory::getService('admin');

        $user=$service->get2(1);

        return $user;
    }
}