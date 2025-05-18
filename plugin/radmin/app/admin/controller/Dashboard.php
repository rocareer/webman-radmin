<?php

namespace plugin\radmin\app\admin\controller;

use plugin\radmin\app\common\controller\Backend;
use Radmin\util\SystemUtil;

class Dashboard extends Backend
{
    public function initialize(): void
    {
        parent::initialize();
    }

    public function index(): \Radmin\Response
    {
     return $this->success('', [
            'remark' => SystemUtil::get_route_remark()
        ]);
    }
}