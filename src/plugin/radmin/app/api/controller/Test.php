<?php

namespace plugin\radmin\app\api\controller;

use plugin\radmin\support\Request;
use support\Response;

class Test
{

    public function index($id): Response
    {

        return json(['1']);
    }
}