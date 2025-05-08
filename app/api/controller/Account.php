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

use Throwable;
use extend\ba\Date;
use extend\ba\Random;
use extend\ba\Captcha;
use app\common\model\User;
use support\lang\Validate;

use app\common\model\UserScoreLog;
use app\common\model\UserMoneyLog;
use app\common\controller\Frontend;
use app\api\validate\Account as AccountValidate;

class Account extends Frontend
{
    protected array $noNeedLogin = ['retrievePassword'];

    protected array $noNeedPermission = ['verification', 'changeBind'];

    public function initialize():void
    {
        parent::initialize();
    }

    public function overview()
    {
        $sevenDays = Date::unixTime('day', -6);
        $score     = $money = $days = [];
        for ($i = 0; $i < 7; $i++) {
            $days[$i]    = date("Y-m-d", $sevenDays + ($i * 86400));
            $tempToday0  = strtotime($days[$i]);
            $tempToday24 = strtotime('+1 day', $tempToday0) - 1;
            $score[$i]   = UserScoreLog::where('user_id', $this->request->member->id)
                ->where('create_time', 'BETWEEN', $tempToday0 . ',' . $tempToday24)
                ->sum('score');

            $userMoneyTemp = UserMoneyLog::where('user_id', $this->request->member->id)
                ->where('create_time', 'BETWEEN', $tempToday0 . ',' . $tempToday24)
                ->sum('money');
            $money[$i]     = bcdiv($userMoneyTemp, 100, 2);
        }

        return $this->success('', [
            'days'  => $days,
            'score' => $score,
            'money' => $money,
        ]);
    }

    /**
     * 会员资料
     * @throws Throwable
     */
    public function profile()
    {
        if ($this->request->isPost()) {
            $data = $this->request->only(['id', 'avatar', 'username', 'nickname', 'gender', 'birthday', 'motto']);
            if (!isset($data['birthday'])) $data['birthday'] = null;

            try {
                $validate = new AccountValidate();
                $validate->scene('edit')->check($data);
            } catch (Throwable $e) {
                return $this->error($e->getMessage());
            }

            $model = $this->auth->getUser();
            $model->startTrans();
            try {
                $model->save($data);
                $model->commit();
            } catch (Throwable $e) {
                $model->rollback();
                return $this->error($e->getMessage());
            }

            return $this->success(__('Data updated successfully~'));
        }

        return $this->success('', [
            'accountVerificationType' => get_account_verification_type()
        ]);
    }

    /**
     * 通过手机号或邮箱验证账户
     * 此处检查的验证码是通过 api/Ems或api/Sms发送的
     * 验证成功后，向前端返回一个 email-pass Token或着 mobile-pass Token
     * 在 changBind 方法中，通过 pass Token来确定用户已经通过了账户验证（用户未绑定邮箱/手机时通过账户密码验证）
     * @throws Throwable
     */
    public function verification()
    {
        $captcha = new Captcha();
        $params  = $this->request->only(['type', 'captcha']);
        if ($captcha->check($params['captcha'], ($params['type'] == 'email' ? $this->auth->email : $this->auth->mobile) . "user_{$params['type']}_verify")) {
            $uuid = Random::uuid();
            Token::set($uuid, $params['type'] . '-pass', $this->request->member->id, 600);
            return $this->success('', [
                'type'                     => $params['type'],
                'accountVerificationToken' => $uuid,
            ]);
        }
        return $this->error(__('Please enter the correct verification code'));
    }

    /**
     * 修改绑定信息（手机号、邮箱）
     * 通过 pass Token来确定用户已经通过了账户验证，也就是以上的 verification 方法，同时用户未绑定邮箱/手机时通过账户密码验证
     * @throws Throwable
     */
    public function changeBind()
    {
        $captcha = new Captcha();
        $params  = $this->request->only(['type', 'captcha', 'email', 'mobile', 'accountVerificationToken', 'password']);
        $user    = $this->auth->getUser();

        if ($user[$params['type']]) {
            if (!Token::check($params['accountVerificationToken'], $params['type'] . '-pass', $user->id)) {
                return $this->error(__('You need to verify your account before modifying the binding information'));
            }
        } elseif (!isset($params['password']) || $user->password != encrypt_password($params['password'], $user->salt)) {
            return $this->error(__('Password error'));
        }

        // 检查验证码
        if ($captcha->check($params['captcha'], $params[$params['type']] . "user_change_{$params['type']}")) {
            if ($params['type'] == 'email') {
                $validate = Validate::rule(['email' => 'require|email|unique:user'])->message([
                    'email.require' => 'email format error',
                    'email.email'   => 'email format error',
                    'email.unique'  => 'email is occupied',
                ]);
                if (!$validate->check(['email' => $params['email']])) {
                    return $this->error(__($validate->getError()));
                }
                $user->email = $params['email'];
            } elseif ($params['type'] == 'mobile') {
                $validate = Validate::rule(['mobile' => 'require|mobile|unique:user'])->message([
                    'mobile.require' => 'mobile format error',
                    'mobile.mobile'  => 'mobile format error',
                    'mobile.unique'  => 'mobile is occupied',
                ]);
                if (!$validate->check(['mobile' => $params['mobile']])) {
                    return $this->error(__($validate->getError()));
                }
                $user->mobile = $params['mobile'];
            }
            Token::delete($params['accountVerificationToken']);
            $user->save();
            return $this->success();
        }
        return $this->error(__('Please enter the correct verification code'));
    }

    public function changePassword()
    {
        if ($this->request->isPost()) {
            $params = $this->request->only(['oldPassword', 'newPassword']);

            if (!$this->auth->checkPassword($params['oldPassword'])) {
                return $this->error(__('Old password error'));
            }

            $model = $this->auth->getUser();
            $model->startTrans();
            try {
                $validate = new AccountValidate();
                $validate->scene('changePassword')->check(['password' => $params['newPassword']]);
                $model->resetPassword($this->request->member->id, $params['newPassword']);
                $model->commit();
            } catch (Throwable $e) {
                $model->rollback();
                return $this->error($e->getMessage());
            }

            $this->auth->logout();
            return $this->success(__('Password has been changed, please login again~'));
        }
    }

    /**
     * 积分日志
     * @throws Throwable
     */
    public function integral()
    {
        $limit         = $this->request->input('limit');
        $integralModel = new UserScoreLog();
        $res           = $integralModel->where('user_id', $this->request->member->id)
            ->order('create_time desc')
            ->paginate($limit);

        return $this->success('', [
            'list'  => $res->items(),
            'total' => $res->total(),
        ]);
    }

    /**
     * 余额日志
     * @throws Throwable
     */
    public function balance()
    {
        $limit      = $this->request->input('limit');
        $moneyModel = new UserMoneyLog();
        $res        = $moneyModel->where('user_id', $this->request->member->id)
            ->order('create_time desc')
            ->paginate($limit);

        return $this->success('', [
            'list'  => $res->items(),
            'total' => $res->total(),
        ]);
    }

    /**
     * 找回密码
     * @throws Throwable
     */
    public function retrievePassword()
    {
        $params = $this->request->only(['type', 'account', 'captcha', 'password']);
        try {
            $validate = new AccountValidate();
            $validate->scene('retrievePassword')->check($params);
        } catch (Throwable $e) {
            return $this->error($e->getMessage());
        }

        if ($params['type'] == 'email') {
            $user = User::where('email', $params['account'])->find();
        } else {
            $user = User::where('mobile', $params['account'])->find();
        }
        if (!$user) {
            return $this->error(__('Account does not exist~'));
        }

        $captchaObj = new Captcha();
        if (!$captchaObj->check($params['captcha'], $params['account'] . 'user_retrieve_pwd')) {
            return $this->error(__('Please enter the correct verification code'));
        }

        if ($user->resetPassword($user->id, $params['password'])) {
            return $this->success(__('Password has been changed~'));
        } else {
            return $this->error(__('Failed to modify password, please try again later~'));
        }
    }
}