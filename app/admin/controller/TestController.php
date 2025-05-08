<?php
namespace app\admin\controller;

use Rocareer\Radmin\Exception\BusinessException;
use support\Request;

class TestController
{
    public function exception(Request $request)
    {
        throw new BusinessException('测试业务异常');
    }
}
