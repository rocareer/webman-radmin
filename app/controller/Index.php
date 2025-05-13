<?php
/**
 * File:        Index.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/14 03:33
 * Description:
 *
 * Copyright [2014-2026] [https://rocareer.com]
 * Licensed under the Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */

namespace app\controller;

use Exception;

class Index
{
	
	public function index()
	{
        try {
            if (!is_file(public_path() . '/install.lock') && is_file(app_path() . '/view/install' . DIRECTORY_SEPARATOR . 'index.html')) {
                return redirect('/install');
            }
            return view('index/index');
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
	
}
