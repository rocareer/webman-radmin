<?php
/*
 *
 *  * // +----------------------------------------------------------------------
 *  * // | Rocareer [ ROC YOUR CAREER ]
 *  * // +----------------------------------------------------------------------
 *  * // | Copyright (c) 2014~2025 Albert@rocareer.com All rights reserved.
 *  * // +----------------------------------------------------------------------
 *  * // | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 *  * // +----------------------------------------------------------------------
 *  * // | Author: albert <Albert@rocareer.com>
 *  * // +----------------------------------------------------------------------
 *
 */

namespace app\api\controller;

use app\common\controller\Api;
use exception\BusinessException;
use extend\ba\Captcha;
use extend\ba\ClickCaptcha;
use support\Response;
use support\StatusCode;
use support\token\Token;
use Throwable;


class Common extends Api
{
    /**
     * 图形验证码
     * @throws Throwable
     */
    public function captcha(): Response
    {
        $captchaId = $this->request->input('id');
        $config    = array(
            'codeSet'  => '123456789',            // 验证码字符集合
            'fontSize' => 22,                     // 验证码字体大小(px)
            'useCurve' => false,                  // 是否画混淆曲线
            'useNoise' => true,                   // 是否添加杂点
            'length'   => 4,                      // 验证码位数
            'bg'       => array(255, 255, 255),   // 背景颜色
        );

        $captcha = new Captcha($config);
        return $captcha->entry($captchaId);
    }

    /**
     * 点选验证码
     */
    public function clickCaptcha()
    {
        $id      = $this->request->input('id');
        $captcha = new ClickCaptcha();
        return $this->success('', $captcha->creat($id));
    }

    /**
     * 点选验证码检查
     * @throws Throwable
     */
    public function checkClickCaptcha()
    {
        $id      = $this->request->post('id');
        $info    = $this->request->post('info');
        $unset   = $this->request->post('unset', false);
        $captcha = new ClickCaptcha();
        if ($captcha->check($id, $info, $unset)) return $this->success();
        return $this->error();
    }

    public function refreshToken()
    {
        $refreshToken = $this->request->post('refreshToken');
        if (empty($refreshToken)){
            throw new BusinessException('凭证已失效:请重新登录 ',StatusCode::NEED_LOGIN, true);
        }
        try {
            $payload = Token::verify($refreshToken);
        } catch (\Exception $e) {
            throw new BusinessException('凭证已失效:请重新登录 ', StatusCode::NEED_LOGIN, true);
        }

        $newToken= Token::refresh($refreshToken);
        return $this->success('', [
            'type'  => $payload->type,
            'token' => $newToken
        ]);
    }
}