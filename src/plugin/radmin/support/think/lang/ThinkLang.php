<?php
/**
 * File:        ThinkLang.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/16 21:37
 * Description: 从 think-lang 迁移,适配 Webman
 *
 * Copyright [2014-2026] [https://rocareer.com]
 * Licensed under the Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */

namespace plugin\radmin\support\think\lang;

use plugin\radmin\support\Request;
use think\facade\Cookie;


/**
 * 多语言管理类
 * @package think
 */
class ThinkLang extends \think\Lang
{

    /**
     * 切换语言
     * @access public
     * @param string $langset 语言
     
     */
    public function switchLangSet(string $langset)
    {
        if (empty($langset)) {
            return;
        }

        $this->setLangSet($langset);

        // 加载语言包
        $basePath = base_path() . '/plugin/radmin';

        // 加载系统语言包
        $files = [
            $basePath . '/lang/' . $langset . '.php'
        ];

        // 加载应用语言包
        $appLangFiles = glob($basePath . '/app/*/lang/' . $langset . '.*');
        if ($appLangFiles) {
            $files = array_merge($files, $appLangFiles);
        }

        // 加载扩展语言包
        $list = config('plugin.radmin.lang.extend_list', []);
        if (isset($list[$langset])) {
            $files = array_merge($files, (array)$list[$langset]);
        }

        // 加载所有语言文件
        foreach ($files as $file) {
            if (is_file($file)) {
                $this->load($file, $langset);
            }
        }
    }
}
