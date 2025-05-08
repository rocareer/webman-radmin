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

/**
 * Here is your custom functions.
 */


use extend\ba\Filesystem;

/**
 * 检查路径是否需要跳过认证
 * @param string|null $path
 * @return bool
 */

if (!function_exists('shouldExclude')) {
    function shouldExclude(?string $path = null): bool
    {
        $path          = $path ?? request()->path();
        $excludeRoutes =  config('auth.exclude', []);

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
    function getEncryptedToken(string $token): string
    {
        $config =  config('token');
        return hash_hmac($config['algo'], $token, $config['key']);
    }
}

/**
 * 获取token
 */
if (!function_exists('getTokenFromRequest')) {
    /**
     * 从请求中获取token
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
        $headers =  config("token.headers.{$request->app}");
        if (empty($headers)) {
            return null;
        }
        foreach ($headers as $header) {
            $token = $request->header($header);
            if (!empty($token)) {
                if (str_starts_with($token, 'Bearer ')) {
                    return trim(str_replace('Bearer ', '', $token));
                }
                return $token;
            }
        }
        // 从query参数或body获取
        return $token ?? $request->input('batoken') ?? null;
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
        $controllerDir = base_path() . '/plugin/radmin/app' . DIRECTORY_SEPARATOR . $app . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR;
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
