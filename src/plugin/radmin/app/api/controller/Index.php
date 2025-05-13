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

use plugin\radmin\app\common\controller\Frontend;
use plugin\radmin\extend\ba\Tree;
use plugin\radmin\support\member\Member;
use upport\think\Db;
use Throwable;

class Index extends Frontend
{
    protected array $noNeedLogin = ['index'];

    public function initialize(): void
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

        //登录用户
        if ($this->request->role==='user') {
            $rules     = [];
            $userMenus = Member::setCurrentRole('user')->getMenus($this->request->member->id);

            // 首页加载的规则，验权，但过滤掉会员中心菜单
            foreach ($userMenus as $item) {
                if ($item['type'] == 'menu_dir') {
                    $menus[] = $item;
                } elseif ($item['type'] != 'menu') {
                    $rules[] = $item;
                }
            }
        } else {
            //未登录用户
            $requiredLogin = $this->request->get('requiredLogin', false);
            if ($requiredLogin) {
                if ($this->request->role!=='user') {
                    return $this->error(__('Please login first'), [
                        'type' => 'need login',
                    ], 303);
                }
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
            'openMemberCenter' => config('buildadmin.open_member_center'),
            'userInfo'         => $this->request->member,
            'menus'            => $menus,
            'rules'            => array_values($rules),

        ];

        return $this->success('Init', $data);
    }
}