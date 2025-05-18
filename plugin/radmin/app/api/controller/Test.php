<?php

namespace plugin\radmin\app\api\controller;

use support\Response;

class Test
{

    public function index($id): Response
    {

        return json(['1']);
    }
}