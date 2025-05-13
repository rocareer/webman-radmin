<?php
/**
 * File      Member.php
 * Author    albert@rocareer.com
 * Time      2025-05-06 07:16:12
 * Describe  Member.php
 */

namespace app\test\controller;

use plugin\radmin\support\member\Factory;
use plugin\radmin\support\member\Member as M;

class Member
{

    public function get()
    {
        $service=Factory::getService('admin');

        $user=$service->get(1);

        $instances=Factory::getServiceInstances();


        // $user=M::role('admin')->get(1);
        // $user=M::role('admin')->get(1);

        return json($user);
    }

    public function get2()
    {
        $service=Factory::getService('admin');

        $user=M::findById(1);

        return $user;
    }
    public function get3()
    {
        $service=Factory::getService('admin');

        $user=M::get3(1);


        return $user;
    }

    public function get4()
    {
        $service=Factory::getService('admin');

        $user=$service->get4(1);

        return $user;
    }
}