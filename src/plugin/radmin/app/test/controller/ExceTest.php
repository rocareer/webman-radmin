<?php
/**
 * File      ExceTest.php
 * Author    albert@rocareer.com
 * Time      2025-05-07 18:57:18
 * Describe  ExceTest.php
 */

namespace app\test\controller;

use exception\Exception;
use exception\UnauthorizedHttpException;
use Throwable;

class ExceTest
{

    public function base()
    {
        try {
            // 模拟抛出异常
           $this->test();
        } catch (Throwable $e) {
            // 再次抛出异常以触发自定义异常处理
            throw $e;
        }
    }

    public function test()
    {
        throw new UnauthorizedHttpException('test', 2001,true,[],'3333333333');
    }
}