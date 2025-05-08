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

namespace app\controller;

class Index
{
	
	public function index()
	{
        try {
            if (!is_file(public_path() . '/install.lock') && is_file(radmin_app() . '/view/install' . DIRECTORY_SEPARATOR . 'index.html')) {
                return redirect('/app/radmin/install');
            }
            return view('index/index');
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
	
}
