<?php

namespace plugin\radmin\app\common\controller;
use plugin\radmin\support\member\Member;
use plugin\radmin\support\StatusCode;
use Radmin\exception\UnauthorizedHttpException;
use service\auth\Auth;
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

    protected mixed $member;

    /**
     * 初始化
     * @throws Throwable
     * @throws HttpResponseException
     */
    public function initialize():void
    {
        parent::initialize();

        $this->member=Member::setCurrentRole($this->request->role);
        $needLogin = !action_in_arr($this->noNeedLogin);

        if ($needLogin) {
            if ($this->request->role!=='user') {
                throw new UnauthorizedHttpException('请先登录' ,StatusCode::NEED_LOGIN,true);
            }
            if (!action_in_arr($this->noNeedPermission)) {
                $routePath = (str_replace('/api/','',$this->request->path()) ?? '');
                if (!$this->member->check($routePath)) {
                    throw new UnauthorizedHttpException('没有权限' ,StatusCode::NO_PERMISSION);
                }
            }
        }

        // 会员验权和登录标签位
        // Event::trigger('frontendInit', $this->auth);
    }
}