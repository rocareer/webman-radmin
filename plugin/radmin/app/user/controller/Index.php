<?php
/**
 * File:        Index.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/12 02:34
 * Description:
 *
 * Copyright [2014-2026] [https://rocareer.com]
 * Licensed under the Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */

/** @noinspection PhpDynamicAsStaticMethodCallInspection */
/** @noinspection PhpUndefinedVariableInspection */


namespace plugin\radmin\app\user\controller;

use plugin\radmin\app\api\validate\User as UserValidate;
use plugin\radmin\app\common\controller\Frontend;
use plugin\radmin\exception\UnauthorizedHttpException;
use plugin\radmin\extend\ba\Captcha;
use plugin\radmin\extend\ba\ClickCaptcha;
use plugin\radmin\support\member\Member;
use plugin\radmin\support\StatusCode;
use Radmin\Response;
use Radmin\token\Token;
use Throwable;

class Index extends Frontend
{
    protected array $noNeedLogin = ['login', 'logout'];

    protected array $noNeedPermission = ['index'];

    public function initialize():void
    {
        parent::initialize();
    }

    /**
     * 会员签入(登录和注册)
     * @throws Throwable
     */
    public function login(): Response
    {
        $openMemberCenter =  config('plugin.radmin.buildadmin.open_member_center');
        if (!$openMemberCenter) {
            return $this->error(__('Member center disabled'));
        }

        // 检查登录态
        // if ($this->auth->isLogin()) {
        //     return $this->success(__('You have already logged in. There is no need to log in again~'), [
        //         'type' => $this->Member::LOGGED_IN
        //     ], $this->Member::LOGIN_RESPONSE_CODE);
        // }

        $userLoginCaptchaSwitch =  config('plugin.radmin.buildadmin.user_login_captcha');

        if ($this->request->isPost()) {
            $params = $this->request->only(['tab', 'email', 'mobile', 'username', 'password', 'keep', 'captcha', 'captchaId', 'captchaInfo', 'registerType']);

            // 提前检查 tab ，然后将以 tab 值作为数据验证场景
            if (!in_array($params['tab'] ?? '', ['login', 'register'])) {
                return $this->error(__('Unknown operation'));
            }

            $validate = new UserValidate();
            try {
                $validate->scene($params['tab'])->check($params);
            } catch (Throwable $e) {
                return $this->error($e->getMessage());
            }

            if ($params['tab'] == 'login') {
                if ($userLoginCaptchaSwitch) {
                    $captchaObj = new ClickCaptcha();
                    if (!$captchaObj->check($params['captchaId'], $params['captchaInfo'])) {
                        return $this->error(__('Captcha error'));
                    }
                }
                $captchaSwitch =  config('plugin.radmin.buildadmin.user_login_captcha');
                $credentials = [
                    'username'      => $this->request->post('username'),
                    'password'      => $this->request->post('password'),
                    'captchaId'     => $this->request->post('captchaId'),
                    'captchaInfo'   => $this->request->post('captchaInfo'),
                    'captchaSwitch' => $captchaSwitch,
                ];

                $res = Member::setCurrentRole('user')->login($credentials, !empty($params['keep']));


            } elseif ($params['tab'] == 'register') {
                $captchaObj = new Captcha();
                if (!$captchaObj->check($params['captcha'], $params[$params['registerType']] . 'user_register')) {
                    return $this->error(__('Please enter the correct verification code'));
                }
                $res = $this->auth->register($params['username'], $params['password'], $params['mobile'], $params['email']);
            }

            if ($res) {
                return $this->success(__('Login succeeded!'), [
                    'userInfo'  => $res,
                    'routePath' => '/user'
                ]);
            } else {

                $msg =  __('Check in failed, please try again or contact the website administrator~');
                return $this->error($msg);
            }
        }

        return $this->success('', [
            'userLoginCaptchaSwitch'  => $userLoginCaptchaSwitch,
            'accountVerificationType' => get_account_verification_type()
        ]);
    }

    /**
     * @throws UnauthorizedHttpException
     */
    public function logout(): Response
    {
        if ($this->request->isPost()) {
            $refreshToken = $this->request->post('refreshToken', '');
            if ($refreshToken) Token::destroy((string)$refreshToken);
            Member::logout();
            return $this->success();
        }
        throw new UnauthorizedHttpException('Unauthorized', __('Unauthorized'),StatusCode::UNAUTHORIZED);
    }
}