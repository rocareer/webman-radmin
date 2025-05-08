<?php

namespace app\common\controller;
use plugin\radmin\service\auth\Auth;
use plugin\radmin\support\member\Member;
use plugin\radmin\support\StatusCode;
use Rocareer\Radmin\Exception\UnauthorizedHttpException;
use think\exception\HttpResponseException;
use Throwable;


class Frontend extends Api
{
    /**
     * 无需登录的方法
     * 访问本控制器的此方法，无需会员登录
     * @var array
     */
    protected array $noNeedLogin = [];

    /**
     * 无需鉴权的方法
     * @var array
     */
    protected array $noNeedPermission = [];

    /**
     * 权限类实例
     * @var Auth
     */
    protected Auth $auth;

    /**
     * 初始化
     * @throws Throwable
     * @throws HttpResponseException
     */
    public function initialize():void
    {
        parent::initialize();

        $needLogin = !action_in_arr($this->noNeedLogin);


        if ($needLogin) {
            throw new UnauthorizedHttpException('请先登录' ,StatusCode::NEED_LOGIN,true);
        }

        if ($needLogin) {
            if (!Member::isLogin()) {
                throw new UnauthorizedHttpException('请先登录' ,StatusCode::NEED_LOGIN,true);
            }
            if (!action_in_arr($this->noNeedPermission)) {
                $routePath = ($this->app->request->controllerPath ?? '') . '/' . $this->request->action(true);
                if (!$this->auth->check($routePath)) {
                    throw new UnauthorizedHttpException('没有权限' ,StatusCode::NO_PERMISSION);
                }
            }
        }

        // 会员验权和登录标签位
        // Event::trigger('frontendInit', $this->auth);
    }
}