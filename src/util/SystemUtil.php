<?php
/**
 * File:        SystemUtil.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/19 02:41
 * Description:
 *
 * Copyright [2014-2026] [https://rocareer.com]
 * Licensed under the Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */

namespace Radmin\util;

use plugin\radmin\app\admin\model\Config as ConfigModel;
use Radmin\Http;
use Radmin\orm\Rdb;

class SystemUtil
{
    public static function get_sys_config(string $name = '', string $group = '', bool $concise = true): mixed
    {
        if (!radminInstalled()) {
            return [];
        }
        if ($name) {
            // 直接使用->value('value')不能使用到模型的类型格式化
            $config = configModel::cache($name, null, configModel::$cacheTag)->where('name', $name)->find();
            if ($config) $config = $config['value'];
        } else {
            if ($group) {
                $temp = configModel::cache('group' . $group, null, configModel::$cacheTag)->where('group', $group)->select()->toArray();
            } else {
                $temp = configModel::cache('sys_config_all', null, configModel::$cacheTag)->order('weigh desc')->select()->toArray();
            }
            if ($concise) {
                $config = [];
                foreach ($temp as $item) {
                    $config[$item['name']] = $item['value'];
                }
            } else {
                $config = $temp;
            }
        }
        return $config;
    }

    public static function get_route_remark(): string
    {
        $controllerName = Http::request()->controller;
        $actionName     = Http::request()->action;
        $path           = str_replace('.', '/', $controllerName);
        $path           = str_replace('plugin\radmin\app\admin\controller\\', '', $path);
        $remark         = Rdb::name('admin_rule')
            ->where('name', $path)
            ->whereOr('name', $path . '/' . $actionName)
            ->value('remark');
        return __((string)$remark);
    }
}