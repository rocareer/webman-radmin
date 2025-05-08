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

use service\auth\Auth;
use Throwable;
use extend\ba\Captcha;
use extend\ba\ClickCaptcha;

use app\common\controller\Frontend;
use app\api\validate\User as UserValidate;

class User extends Frontend
{
    protected array $noNeedLogin = ['checkIn', 'logout'];

    protected array $noNeedPermission = ['index'];

    public function initialize():void
    {
        parent::initialize();
    }

    /**
     * 会员签入(登录和注册)
     * @throws Throwable
     */
    public function checkIn()
    {
        $openMemberCenter = radmin_config('buildadmin.open_member_center');
        if (!$openMemberCenter) {
            return $this->error(__('Member center disabled'));
        }

        // 检查登录态
        // if ($this->auth->isLogin()) {
        //     return $this->success(__('You have already logged in. There is no need to log in again~'), [
        //         'type' => $this->Member::LOGGED_IN
        //     ], $this->Member::LOGIN_RESPONSE_CODE);
        // }

        $userLoginCaptchaSwitch = radmin_config('buildadmin.user_login_captcha');

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
                $captchaSwitch = radmin_config('buildadmin.user_login_captcha');
                $credentials = [
                    'username'      => $this->request->post('username'),
                    'password'      => $this->request->post('password'),
                    'captchaId'     => $this->request->post('captchaId'),
                    'captchaInfo'   => $this->request->post('captchaInfo'),
                    'captchaSwitch' => $captchaSwitch,
                ];

                $res = Member::login($credentials,'user', !empty($params['keep']));


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

    public function logout()
    {
        if ($this->request->isPost()) {
            $refreshToken = $this->request->post('refreshToken', '');
            if ($refreshToken) Token::delete((string)$refreshToken);
            $this->auth->logout();
            return $this->success();
        }
    }
}