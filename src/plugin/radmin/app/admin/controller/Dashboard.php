<?php

namespace plugin\radmin\app\admin\controller;

use plugin\radmin\app\common\controller\Backend;

class Dashboard extends Backend
{
    public function initialize(): void
    {
        parent::initialize();
    }

    public function index(): \support\Response
    {
     return $this->success('', [
            'remark' => get_route_remark()
        ]);
    }
}