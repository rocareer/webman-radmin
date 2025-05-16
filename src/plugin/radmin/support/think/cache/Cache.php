<?php
/**
 * File:        Cache.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/16 21:24
 * Description: 适配应用 基于 think-cache
 *
 * Copyright [2014-2026] [https://rocareer.com]
 * Licensed under the Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */

namespace plugin\radmin\support\think\cache;

use think\Facade;

class Cache extends Facade
{
    /**
     * 获取当前Facade对应类名（或者已经绑定的容器对象标识）.
     *
     * @return string
     */
    protected static function getFacadeClass(): string
    {
        return CacheManager::class;
    }
}