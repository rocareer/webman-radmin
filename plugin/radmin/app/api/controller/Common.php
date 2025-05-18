<?php


namespace plugin\radmin\app\api\controller;

use Exception;
use plugin\radmin\app\common\controller\Api;
use plugin\radmin\exception\BusinessException;
use plugin\radmin\extend\ba\Captcha;
use plugin\radmin\extend\ba\ClickCaptcha;
use plugin\radmin\support\StatusCode;
use Radmin\Response;
use Radmin\token\Token;
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
    public function clickCaptcha(): Response
    {
        $id      = $this->request->input('id');
        $captcha = new ClickCaptcha();
        return $this->success('', $captcha->creat($id));
    }

    /**
     * 点选验证码检查
     * @throws Throwable
     */
    public function checkClickCaptcha(): Response
    {
        $id      = $this->request->post('id');
        $info    = $this->request->post('info');
        $unset   = $this->request->post('unset', false);
        $captcha = new ClickCaptcha();
        if ($captcha->check($id, $info, $unset)) return $this->success();
        return $this->error();
    }

    /**
     * @throws BusinessException
     */
    public function refreshToken(): Response
    {
        $refreshToken = $this->request->post('refreshToken');
        if (empty($refreshToken)) {
            throw new BusinessException('登录已超时:请重新登录 ', StatusCode::NEED_LOGIN, true);
        }
        try {
            $payload  = Token::verify($refreshToken);
            $newToken = Token::refresh($refreshToken);
        } catch (Exception) {
            throw new BusinessException('登录已超时:请重新登录 ', StatusCode::NEED_LOGIN, true);
        }
        return $this->success('', [
            'type'  => $payload->role.'-refresh',
            'token' => $newToken
        ]);
    }
}