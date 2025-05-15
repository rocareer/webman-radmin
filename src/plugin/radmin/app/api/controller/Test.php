<?php

namespace plugin\radmin\app\api\controller;

use plugin\radmin\support\Request;
use support\Response;

class Test
{

    public function index($id): Response
    {
        var_dump($id);
        var_dump(request()->input('id'));
        return json(['1']);
    }
}