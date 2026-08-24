<?php
/**
 * File:        Cache.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/16 21:24
 * Description: 适配应用 基于 think-cache
 *
 * Copyright (c) Rocareer Team. All rights reserved.
 * Author: albert@rocareer.com
 */

namespace Radmin\cache;

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