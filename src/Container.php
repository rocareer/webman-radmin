<?php
/**
 * File:        Container.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/16 20:39
 * Description:
 *
 * Copyright (c) Rocareer Team. All rights reserved.
 * Author: albert@rocareer.com
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