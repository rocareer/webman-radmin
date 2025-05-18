<?php


namespace plugin\radmin\app\controller;

class Index
{
	
	public function index()
	{
        try {
            if (!is_file(public_path() . '/install.lock') && is_file(base_path() . '/plugin/radmin/app/view/install' . DIRECTORY_SEPARATOR . 'index.html')) {
                return redirect('/app/radmin/install');
            }
            return view('index/index');
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
	
}
