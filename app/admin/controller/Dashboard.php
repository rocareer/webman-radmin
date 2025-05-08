<?php

namespace app\admin\controller;

use app\common\controller\Backend;

class Dashboard extends Backend
{
    public function initialize(): void
    {
        parent::initialize();
    }

    public function index()
    {
     return $this->success('', [
            'remark' => get_route_remark()
        ]);
    }
}