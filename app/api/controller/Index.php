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

use app\common\controller\Frontend;
use extend\ba\Tree;
use support\member\Member;
use support\think\Db;
use Throwable;

class Index extends Frontend
{
    protected array $noNeedLogin = ['index'];

    public function initialize():void
    {
        parent::initialize();
    }

    /**
     * 前台和会员中心的初始化请求
     *
     * @throws Throwable
     */
    public function index()
    {
        $menus = [];

        if ($this->request->adminIsLogin) {
            $rules     = [];
            $userMenus = AdminHelper::getMenus();

            // 首页加载的规则，验权，但过滤掉会员中心菜单
            foreach ($userMenus as $item) {
                if ($item['type'] == 'menu_dir') {
                    $menus[] = $item;
                } elseif ($item['type'] != 'menu') {
                    $rules[] = $item;
                }
            }
        } else {
            // 若是从前台会员中心内发出的请求，要求必须登录，否则会员中心异常

            $userInfo=[];
            if (Member::isLogin()){
                $userInfo=Member::getUserInfo($this->request->auth->user->id);
            }
            $requiredLogin = $this->request->get('requiredLogin', false);
            if ($requiredLogin) {

                // 触发可能的 token 过期异常

                //todo 这里检查 token 是否过期

                //				return $this->error(__('Please login first'), [
                //					'type' => $this->Member::NEED_LOGIN,
                //				], $this->Member::LOGIN_RESPONSE_CODE);
            }

            $rules         = Db::name('user_rule')
                ->where('status', '1')
                ->where('no_login_valid', 1)
                ->where('type', 'in', [
                    'route',
                    'nav',
                    'button',
                ])
                ->order('weigh', 'desc')
                ->select()
                ->toArray();
            $rules         = Tree::instance()
                ->assembleChild($rules);
            $data['rules'] = array_values($rules);
        }


        $data = [
            'site'             => [
                'siteName'     => get_sys_config('site_name'),
                'recordNumber' => get_sys_config('record_number'),
                'version'      => get_sys_config('version'),
                'cdnUrl'       => full_url(),
                'upload'       => keys_to_camel_case(get_upload_config(), [
                    'max_size',
                    'save_name',
                    'allowed_suffixes',
                    'allowed_mime_types',
                ]),
            ],
            'openMemberCenter' => config('plugin.radmin.buildadmin.open_member_center'),
            'userInfo'         => $userInfo,
            'menus'            => $menus,
            'rules'            => array_values($rules),

        ];

        return $this->success('Init', $data);
    }
}