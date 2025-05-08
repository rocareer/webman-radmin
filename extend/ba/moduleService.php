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

namespace extend\ba;

use app\admin\library\module\Server;
use think\Service;
use Webman\Event\Event;

class moduleService extends Service
{
    public function register()
    {
        $this->moduleAppInit();
    }

    public function moduleAppInit()
    {
        $installed = Server::installedList(root_path() . 'modules' . DIRECTORY_SEPARATOR);
        foreach ($installed as $item) {
            if ($item['state'] != 1) {
                continue;
            }
            $moduleClass = Server::getClass($item['uid']);
            if (class_exists($moduleClass)) {
                if (method_exists($moduleClass, 'AppInit')) {
                    Event::listen('AppInit', function () use ($moduleClass) {
                        $handle = new $moduleClass();
                        $handle->AppInit();
                    });
                }
            }
        }
    }
}