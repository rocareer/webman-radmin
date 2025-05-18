<?php
/**
 * File:        Container.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/16 20:39
 * Description:
 *
 * Copyright [2014-2026] [https://rocareer.com]
 * Licensed under the Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */

namespace Radmin;

class Container extends \support\Container
{

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic(string $name, array $arguments)
    {
        $plugin = 'radmin';
        return static::instance($plugin)->{$name}(... $arguments);
    }
}