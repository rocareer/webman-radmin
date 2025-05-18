<?php

/**
 * File:        Helper.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/11 13:31
 * Description:
 *
 * Copyright [2014-2026] [https://rocareer.com]
 * Licensed under the Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */


use Radmin\util\FileUtil;
use Radmin\Http;
use think\helper\Str;

if (!function_exists('formatBytes')) {
    /**
     * 将字节大小转换为人类可读的格式
     * @param int $bytes     字节数
     * @param int $precision 精度
     * @return string 格式化后的大小
     */
    function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow   = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow   = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
if (!function_exists('unitToByte')) {

    /**
     * 单位转为字节
     * @param string $unit 将b、kb、m、mb、g、gb的单位转为 byte
     * @return   int
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/18 22:42
     */
    function unitToByte(string $unit): int
    {
        preg_match('/([0-9.]+)(\w+)/', $unit, $matches);
        if (!$matches) {
            return 0;
        }
        $typeDict = ['b' => 0, 'k' => 1, 'kb' => 1, 'm' => 2, 'mb' => 2, 'gb' => 3, 'g' => 3];
        return (int)($matches[1] * pow(1024, $typeDict[strtolower($matches[2])] ?? 0));

    }
}

if (!function_exists('radmin_path')) {
    /**
     * @return   string
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/19 01:41
     */
    function radmin_path(): string
    {
       return base_path() . DIRECTORY_SEPARATOR . 'plugin' . DIRECTORY_SEPARATOR . 'radmin';
    }
}
if (!function_exists('radmin_config')) {
    /**
     * @param $name
     * @param $default
     * @return   mixed
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/19 01:41
     */
    function radmin_config($name, $default = null): mixed
    {
        return config("plugin.radmin.$name", $default);
    }
}

if (!function_exists('getDbPrefix')) {
    /**
     * 获取数据库表前缀
     * @return   array|false|mixed|string
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/17 02:34
     */
    function getDbPrefix()
    {
        return getenv('THINKORM_DEFAULT_PREFIX')??config('plugin.radmin.think-orm.connections.mysql.prefix');
    }
}

if (!function_exists('parseClass')) {
    /**
     * 自动解析验证器类名称
     * @param string $layer
     * @param string $name
     * @return   string
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/16 15:10
     */
    function parseClass(string $layer, string $name): string
    {
        $name  = str_replace(['/', '.'], '\\', $name);
        $array = explode('\\', $name);
        $class = Str::studly(array_pop($array));
        $path  = $array ? implode('\\', $array) . '\\' : '';

        return 'plugin\\radmin\\app\\' . Http::request()->app . '\\' . $layer . '\\' . $path . $class;
    }
}
if (!function_exists('arrayStrictFilter')) {
    /**
     * 过滤数组
     * @param array $input
     * @param array $allowedKeys
     * @return   array
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/11 20:48
     */
    function arrayStrictFilter(array $input, array $allowedKeys): array
    {
        // 检查字段数量是否一致
        if (count($input) !== count($allowedKeys)) {
            throw new InvalidArgumentException('输入数组的字段数量与允许的字段数量不一致');
        }

        // 检查字段名称是否完全匹配
        $inputKeys = array_keys($input);
        if ($inputKeys !== $allowedKeys) {
            throw new InvalidArgumentException('输入数组的字段名称与允许的字段名称不匹配');
        }

        // 返回过滤后的数组
        return array_intersect_key($input, array_flip($allowedKeys));
    }
}

if (!function_exists('radminInstalled')) {

    function radminInstalled(): bool
    {
        $lockedFile=base_path().'/plugin/radmin/public/install.lock';
        if (file_exists($lockedFile)) {
            return true;
        }
        return false;
    }
}
if (!function_exists('radminOrmInstalled')) {

    function radminOrmInstalled(): bool
    {
        if (getenv('THINKORM_DEFAULT_PASSWORD')) {
            return true;
        }
        return false;
    }
}

/**
 * 检查路径是否需要跳过认证
 * @param string|null $path
 * @return bool
 */

if (!function_exists('shouldExclude')) {
    function shouldExclude(?string $path = null): bool
    {
        $path          = $path ?? Http::request()->path();
        $excludeRoutes = config('plugin.radmin.auth.exclude', []);

        foreach ($excludeRoutes as $route) {
            if (strpos($route, '*') !== false) {
                // 处理通配符路径
                $pattern = '#^' . str_replace('\*', '.*', preg_quote($route, '#')) . '$#';
                if (preg_match($pattern, $path)) {
                    return true;
                }
            } elseif ($path === $route) {
                return true;
            }
        }
        return false;
    }
}


if (!function_exists('getEncryptedToken')) {

    /**
     * 获取加密后的token
     * By albert  2025/05/06 17:32:42
     * @param string $token
     * @return string
     */
    function getEncryptedToken(string $token, $algo = 'sha256', $key = 'rocareer'): string
    {
        return hash_hmac($algo, $token, $key);
    }
}

/**
 * 获取token
 */
if (!function_exists('getTokenFromRequest')) {
    /**
     * 从请求中获取token
     * 新增 api-token 为前端下载等用
     * By albert  2025/05/06 17:30:58
     * @param null  $request
     * @param array $names
     * @return string|null
     * @throws Exception
     */
    function getTokenFromRequest($request = null, array $names = []): ?string
    {
        if (!empty($names)) {
            return get_ba_token($names);
        }

        $request = $request ?? request();

        // 从 Authorization 头获取 Bearer Token
        $token = $request->header('Authorization');
        if (!empty($token) && str_starts_with($token, 'Bearer ')) {
            return extractBearerToken($token);
        }

        $type=$request->role??$request->app;
        // 从配置的 headers 中获取 Token
        $headers = config("plugin.radmin.auth.headers.{$type}", []);
        foreach ($headers as $header) {
            $token = $request->header($header);
            if (!empty($token)) {
                return $token;
            }
        }



        /**
         * api-token 给前端 下载等用
         * 从 query 参数或 body 获取 Token
         */

        return $request->input('api-token') ??$request->input('batoken');
    }

    /**
     * 从 Authorization 头中提取 Bearer Token
     * @param string $token
     * @return   string
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/11 13:30
     */
    function extractBearerToken(string $token): string
    {
        return trim(str_replace('Bearer ', '', $token));
    }
}


if (!function_exists('get_ba_token')) {
    /**
     * 获取auth token
     * By albert  2025/05/06 18:24:39
     * @param array $names
     * @return string
     */
    function get_ba_token(array $names = ['ba', 'token']): string
    {
        $separators = [
            'header' => ['', '-'], // batoken、ba-token【ba_token 不在 header 的接受列表内因为兼容性不高，改用 http_ba_token】
            'param'  => ['', '-', '_'], // batoken、ba-token、ba_token
            'server' => ['_'], // http_ba_token
        ];

        $tokens  = [];
        $request = request();
        foreach ($separators as $fun => $sps) {
            foreach ($sps as $sp) {
                $tokens[] = $request->$fun(($fun == 'server' ? 'http_' : '') . implode($sp, $names));
            }
        }
        $tokens = array_filter($tokens);
        return array_values($tokens)[0] ?? '';
    }
}


if (!function_exists('arrayToObject')) {
    /**
     * 将数组转换为对象
     * By albert  2025/05/05 02:29:14
     * @param array $array
     * @return object
     */
    function arrayToObject(array $array): object
    {
        return json_decode(json_encode($array));
    }
}


if (!function_exists('jsonToArray')) {
    /**
     * 将 JSON 字符串或数组转换为 PHP 数组
     * By albert  2025/04/19 20:19:21
     *
     * @param $data
     *
     * @return mixed|string
     */
    function jsonToArray($data): mixed
    {
        // 判断是否是数组
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                // 递归处理嵌套数组
                $data[$key] = jsonToArray($value);
            }
        } elseif (is_string($data)) {
            // 判断是否是 JSON 字符串
            $decoded = json_decode($data, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                // 如果是有效的 JSON，则解析为数组
                return jsonToArray($decoded);
            }
        }
        // 如果不是 JSON 或数组，直接返回原值
        return $data;
    }
}


if (!function_exists('root_path')) {
    /**
     * 获取项目根目录
     *
     * @param string $path
     *
     * @return string
     */
    function root_path(string $path = '')
    {
        return base_path() . '/' . $path . '/';
    }
}


if (!function_exists('clean_xss')) {
    /**
     * 清除XSS 无依赖版本
     * By albert  2025/04/15 19:10:24
     *
     * @param string $string
     *
     * @return string
     */
    function clean_xss(string $string): string
    {
        // 移除不可见字符
        $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/u', '', $string);

        // 移除危险的HTML属性
        $string = preg_replace_callback('/<[^>]+>/i', function ($matches) {
            $tags = 'img|a|div|p|span|table|tr|td|th|ul|ol|li|h1|h2|h3|h4|h5|h6|pre|code|strong|em|b|i|u|strike|br|hr';

            $tag = preg_replace('/<([a-z]+)([^>]*)>/i', '$1', $matches[0]);
            if (!preg_match("/^($tags)$/i", $tag)) {
                return '';
            }

            return preg_replace_callback('/\s([a-z_-]+)\s*=\s*(["\'])(.*?)\2/i', function ($attrMatches) {
                $safeAttributes = 'href|src|alt|title|class|style|width|height|align|border|cellpadding|cellspacing|colspan|rowspan|valign';
                if (preg_match("/^($safeAttributes)$/i", $attrMatches[1])) {
                    // 对URL类属性进行特殊处理
                    if (in_array(strtolower($attrMatches[1]), [
                        'href',
                        'src',
                    ])) {
                        $url = htmlspecialchars_decode($attrMatches[3]);
                        if (!preg_match('/^(https?:|mailto:|tel:|#)/i', $url)) {
                            return '';
                        }
                        return ' ' . $attrMatches[1] . '=' . $attrMatches[2] . htmlspecialchars($url) . $attrMatches[2];
                    }
                    return $attrMatches[0];
                }
                return '';
            }, $matches[0]);
        }, $string);

        // 移除危险的JavaScript协议
        $string = preg_replace('/(javascript|jscript|vbscript|data):/i', '', $string);

        // 转换特殊字符
        $string = htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // 二次过滤，确保安全
        $string = strip_tags($string, '<p><a><img><div><span><br><hr><h1><h2><h3><h4><h5><h6><ul><ol><li><table><tr><td><th><pre><code><strong><em><b><i><u><strike>');

        return $string;
    }
}


if (!function_exists('get_controller_list')) {
    /**
     * 获取控制器列表
     * By albert  2025/04/14 18:48:39
     *
     * @param string $app
     *
     * @return array
     */
    function get_controller_list(string $app = 'admin'): array
    {
        // todo
        // $controllerDir = app_path() . DIRECTORY_SEPARATOR . $app . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR;
        $controllerDir = base_path().'/plugin/radmin/app' . DIRECTORY_SEPARATOR . $app . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR;
        return Filesystem::getDirFiles($controllerDir);
    }
}

if (!function_exists('parse_name')) {
    /**
     * 字符串命名风格转换
     * type 0 将Java风格转换为C的风格 1 将C风格转换为Java的风格
     * @param string $name    字符串
     * @param int    $type    转换类型
     * @param bool   $ucfirst 首字母是否大写（驼峰规则）
     * @return string
     */
    function parse_name(string $name, int $type = 0, bool $ucfirst = true): string
    {
        if ($type) {
            $name = preg_replace_callback('/_([a-zA-Z])/', function ($match) {
                return strtoupper($match[1]);
            }, $name);

            return $ucfirst ? ucfirst($name) : lcfirst($name);
        }

        return strtolower(trim(preg_replace('/[A-Z]/', '_\\0', $name), '_'));
    }
}
