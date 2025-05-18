<?php
/**
 * File:        CacheManger.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/16 21:22
 * Description: 适配 Radmin 应用
 *
 * Copyright [2014-2026] [https://rocareer.com]
 * Licensed under the Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */

namespace Radmin\cache;
class CacheManager extends  \Webman\ThinkCache\CacheManager
{
    /**
     * @var string|null
     */
    protected ?string $namespace = '\\Webman\\ThinkCache\\driver\\';

    /**
     * 默认驱动
     * @return string|null
     */
    public function getDefaultDriver(): ?string
    {
        return $this->getConfig('default');
    }

    /**
     * 获取缓存配置
     * @access public
     * @param string     $name    名称
     * @param mixed|null $default 默认值
     * @return mixed
     */
    public function getConfig(string $name = '', mixed $default = null): mixed
    {
        if ($name) {
            return config("plugin.radmin.cache.$name", $default);
        }
        return config('plugin.radmin.cache');
    }

}