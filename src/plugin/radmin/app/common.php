<?php
/**
 * File:        common.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/10 23:15
 * Description:
 *
 * Copyright [2014-2026] [https://rocareer.com]
 * Licensed under the Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */

// +----------------------------------------------------------------------
// | Webman插件公共函数库
// | by albert@rocareer.com
// +----------------------------------------------------------------------
// | 迁移自ThinkPHP项目，适配Webman框架规范
// | 主要变更：
// | 1. 替换ThinkPHP特有类引用为Webman兼容版本
// | 2. 修改配置读取方式(radmin_config → config)
// | 3. 更新数据库操作方式(使用webman/think-orm)
// | 4. 更新异常处理方式(使用webman/think-exception)

// +----------------------------------------------------------------------

use app\admin\library\module\Server;
use plugin\radmin\extend\ba\Filesystem;
use plugin\radmin\support\think\Lang;
use plugin\radmin\support\think\Db;
use app\admin\model\Config as configModel;


if (!function_exists('__')) {
    /**
     * 语言翻译
     *
     * @param string $name 被翻译字符
     * @param array  $vars 替换字符数组
     * @param string $lang 翻译语言
     *
     * @return mixed
     * @变更说明：保持原功能，仅替换Lang类引用
     */
    function __(string $name, array $vars = [], string $lang = ''): mixed
    {
        if (is_numeric($name) || !$name) {
            return $name;
        }

        return Lang::get($name, $vars, $lang);
    }
}

if (!function_exists('filter')) {
    /**
     * 输入过滤
     * 富文本反XSS请使用 clean_xss，也就不需要及不能再 filter 了
     *
     * @param string $string 要过滤的字符串
     *
     * @return string
     * @变更说明：无需修改，无框架依赖
     */
    function filter(string $string): string
    {
        $string = trim($string);
        $string = strip_tags($string);
        return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, 'UTF-8');
    }
}


if (!function_exists('htmlspecialchars_decode_improve')) {
    /**
     * html解码增强
     * 被 filter函数 内的 htmlspecialchars 编码的字符串，需要用此函数才能完全解码
     *
     * @param string $string
     * @param int    $flags
     *
     * @return string
     * @变更说明：无需修改，无框架依赖
     */
    function htmlspecialchars_decode_improve(string $string, int $flags = ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401): string
    {
        return htmlspecialchars_decode($string, $flags);
    }
}


if (!function_exists('get_sys_config')) {

    /**
     * 获取站点的系统配置，不传递参数则获取所有配置项
     * @param string $name    变量名
     * @param string $group   变量分组，传递此参数来获取某个分组的所有配置项
     * @param bool   $concise 是否开启简洁模式，简洁模式下，获取多项配置时只返回配置的键值对
     * @return mixed
     * @throws Throwable
     */
    function get_sys_config(string $name = '', string $group = '', bool $concise = true): mixed
    {
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
}

if (!function_exists('get_route_remark')) {
    /**
     * 获取当前路由后台菜单规则的备注信息
     *
     * @return string
     * @变更说明：修改数据库操作方式，使用support\think\Db
     */
    function get_route_remark(): string
    {
        $controllerName = request()->controller;
        $actionName     = request()->action;
        $path           = str_replace('.', '/', $controllerName);
        $path           = str_replace('app\admin\controller\\', '', $path);
        $remark         = Db::name('admin_rule')
            ->where('name', $path)
            ->whereOr('name', $path . '/' . $actionName)
            ->value('remark');
        return __((string)$remark);
    }
}

if (!function_exists('getProtocol')) {
    // 适用于需要自定义处理的场景
    /**
     * 获取当前请求的协议
     * By albert  2025/04/15 14:45:41
     *
     * @param $request
     *
     * @return string
     */
    function getProtocol($request): string
    {
        // 优先级 1：处理反向代理转发 (如 Nginx)
        if ($request->header('x-forwarded-proto')) {
            return strtolower($request->header('x-forwarded-proto'));
        }

        // 优先级 2：直接判断 HTTPS 标识
        //		if($request->server('https') && $request->server('https') !== 'off') {
        //			return 'https';
        //		}

        // 优先级 3：通过端口判断
        //		return $request->server('server_port') == 443 ? 'https' : 'http';
        return $request->isSsl() ? 'https' : 'http';
    }
}


if (!function_exists('full_url')) {
    /**
     * 获取资源完整url地址；若安装了云存储或配置了CdnUrl，则自动使用对应的CdnUrl
     *
     * 命令行可用
     * @param string|null $relativeUrl 资源相对地址 不传入则获取域名
     * @param string|bool $domain      是否携带域名 或者直接传入域名
     * @param string      $default     默认值
     *
     * @return string
     */
    function full_url(?string $relativeUrl = null, string|bool $domain = true, string $default = ''): string
    {
        // 存储/上传资料配置 todo
        //		Event::trigger('uploadConfigInit', App::getInstance());

        $cdnUrl = config('buildadmin.cdn_url');
        if (!$cdnUrl) {
            if (request()) {
                $cdnUrl = request()->upload['cdn'] ?? '//' . request()->host();
            } else {
                $cdnUrl = get_sys_config('host');
            }

        }

        if ($domain === true) {
            $domain = $cdnUrl;
        } elseif ($domain === false) {
            $domain = '';
        }

        $relativeUrl = $relativeUrl ?: $default;
        if (!$relativeUrl)
            return $domain;

        $regex = "/^((?:[a-z]+:)?\/\/|data:image\/)(.*)/i";
        if (preg_match('/^http(s)?:\/\//', $relativeUrl) || preg_match($regex, $relativeUrl) || $domain === false) {
            return $relativeUrl;
        }

        $url          = $domain . $relativeUrl;
        $cdnUrlParams = config('buildadmin.cdn_url_params');
        if ($domain === $cdnUrl && $cdnUrlParams) {
            $separator = str_contains($url, '?') ? '&' : '?';
            $url       .= $separator . $cdnUrlParams;
        }

        return $url;
    }
}


if (!function_exists('set_timezone')) {
    /**
     * 设置时区
     *
     * @throws Throwable
     * @变更说明：修改配置读取方式(radmin_config → config)
     */
    function set_timezone($timezone = null): void
    {

        $defaultTimezone = config('radmin.default_timezone');

        $timezone = is_null($timezone) ? get_sys_config('time_zone') : $timezone;
        if ($timezone && $defaultTimezone != $timezone) {
            date_default_timezone_set($timezone);
        }
    }
}

function get_upload_config(): array
{
    //	Event::dispatch('uploadConfigInit', []);

    $uploadConfig = config('upload', []);

    // 确保有默认配置
    if (empty($uploadConfig)) {
        $uploadConfig = [
            'max_size' => '10M',
            // 默认10MB
            'mode'     => 'local',
            // 其他默认配置...
        ];
    }

    // 确保max_size存在
    if (!isset($uploadConfig['max_size'])) {
        $uploadConfig['max_size'] = '10M';
    }

    $uploadConfig['max_size'] = Filesystem::fileUnitToByte($uploadConfig['max_size']);

    $upload = request()->upload;
    if (!$upload) {
        $uploadConfig['mode'] = 'local';
        return $uploadConfig;
    }
    unset($upload['cdn']);
    return array_merge($upload, $uploadConfig);
}


if (!function_exists('str_attr_to_array')) {
    /**
     * 将字符串属性列表转为数组
     *
     * @param string $attr 属性，一行一个，无需引号
     *
     * @return array
     * @变更说明：无需修改，无框架依赖
     */
    function str_attr_to_array(string $attr): array
    {
        if (!$attr)
            return [];
        $attr     = explode("\n", trim(str_replace("\r\n", "\n", $attr)));
        $attrTemp = [];
        foreach ($attr as $item) {
            $item = explode('=', $item);
            if (isset($item[0]) && isset($item[1])) {
                $attrVal = $item[1];
                if ($item[1] === 'false' || $item[1] === 'true') {
                    $attrVal = !($item[1] === 'false');
                } elseif (is_numeric($item[1])) {
                    $attrVal = (float)$item[1];
                }
                if (strpos($item[0], '.')) {
                    $attrKey = explode('.', $item[0]);
                    if (isset($attrKey[0]) && isset($attrKey[1])) {
                        $attrTemp[$attrKey[0]][$attrKey[1]] = $attrVal;
                        continue;
                    }
                }
                $attrTemp[$item[0]] = $attrVal;
            }
        }
        return $attrTemp;
    }
}

if (!function_exists('action_in_arr')) {
    /**
     * 检测一个方法是否在传递的数组内
     *
     * @param array $arr
     *
     * @return bool
     * @变更说明：修改请求方法适配
     */
    function action_in_arr(array $arr = []): bool
    {
        $arr = is_array($arr) ? $arr : explode(',', $arr);
        if (!$arr) {
            return false;
        }
        $arr           = array_map('strtolower', $arr);
        $currentAction = request()->action ?? '';
        if (in_array(strtolower($currentAction), $arr) || in_array('*', $arr)) {
            return true;
        }
        return false;
    }
}

if (!function_exists('build_suffix_svg')) {
    /**
     * 构建文件后缀的svg图片
     *
     * @param string      $suffix     文件后缀
     * @param string|null $background 背景颜色
     *
     * @return string
     * @变更说明：无需修改，无框架依赖
     * @noinspection HtmlDeprecatedAttribute
     * @noinspection XmlUnusedNamespaceDeclaration
     */
    function build_suffix_svg(string $suffix = 'file', ?string $background = null): string
    {
        $suffix = mb_substr(strtoupper($suffix), 0, 4);
        $total  = unpack('L', hash('adler32', $suffix, true))[1];
        $hue    = $total % 360;
        [
            $r,
            $g,
            $b,
        ] = hsv2rgb($hue / 360, 0.3, 0.9);

        $background = $background ?: "rgb($r,$g,$b)";

        return '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
            <path style="fill:#E2E5E7;" d="M128,0c-17.6,0-32,14.4-32,32v448c0,17.6,14.4,32,32,32h320c17.6,0,32-14.4,32-32V128L352,0H128z"/>
            <path style="fill:#B0B7BD;" d="M384,128h96L352,0v96C352,113.6,366.4,128,384,128z"/>
            <polygon style="fill:#CAD1D8;" points="480,224 384,128 480,128 "/>
            <path style="fill:' . $background . ';" d="M416,416c0,8.8-7.2,16-16,16H48c-8.8,0-16-7.2-16-16V256c0-8.8,7.2-16,16-16h352c8.8,0,16,7.2,16,16 V416z"/>
            <path style="fill:#CAD1D8;" d="M400,432H96v16h304c8.8,0,16-7.2,16-16v-16C416,424.8,408.8,432,400,432z"/>
            <g><text><tspan x="220" y="380" font-size="124" font-family="Verdana, Helvetica, Arial, sans-serif" fill="white" text-anchor="middle">' . $suffix . '</tspan></text></g>
        </svg>';
    }
}

if (!function_exists('get_area')) {
    /**
     * 获取省份地区数据
     *
     * @param int|null $cacheTime 缓存时间(秒)
     *
     * @return array
     * @throws Throwable
     */
    function get_area(?int $cacheTime = 86400): array
    {
        $province = request()->get('province', '');
        $city     = request()->get('city', '');

        $where = [
            'pid'   => 0,
            'level' => 1,
        ];
        if ($province !== '') {
            $where['pid']   = $province;
            $where['level'] = 2;
            if ($city !== '') {
                $where['pid']   = $city;
                $where['level'] = 3;
            }
        }

        $cacheKey = 'area_data_' . md5(serialize($where));

        return Db::name('area')
            ->cache($cacheKey, $cacheTime)
            ->where($where)
            ->field('id as value,name as label')
            ->select()
            ->toArray();
    }
}

if (!function_exists('hsv2rgb')) {
    /**
     * HSV转RGB
     *
     * @param float $h 色相
     * @param float $s 饱和度
     * @param float $v 明度
     *
     * @return array
     * @变更说明：无需修改，无框架依赖
     */
    function hsv2rgb(float $h, float $s, float $v): array
    {
        $r = $g = $b = 0;

        $i = floor($h * 6);
        $f = $h * 6 - $i;
        $p = $v * (1 - $s);
        $q = $v * (1 - $f * $s);
        $t = $v * (1 - (1 - $f) * $s);

        switch ($i % 6) {
            case 0:
                $r = $v;
                $g = $t;
                $b = $p;
                break;
            case 1:
                $r = $q;
                $g = $v;
                $b = $p;
                break;
            case 2:
                $r = $p;
                $g = $v;
                $b = $t;
                break;
            case 3:
                $r = $p;
                $g = $q;
                $b = $v;
                break;
            case 4:
                $r = $t;
                $g = $p;
                $b = $v;
                break;
            case 5:
                $r = $v;
                $g = $p;
                $b = $q;
                break;
        }

        return [
            floor($r * 255),
            floor($g * 255),
            floor($b * 255),
        ];
    }
}

if (!function_exists('ip_check')) {
    /**
     * IP检查
     *
     * @throws Throwable
     */
    function ip_check(): void
    {
        $no_access_ip = get_sys_config('no_access_ip');
        // 确保总是返回数组
        $no_access_ip = is_array($no_access_ip) ? $no_access_ip : [];

        $ip = request()->getRealIp();
        if (in_array($ip, $no_access_ip)) {
            throw new Exception('IP not allowed');
        }
    }
}


if (!function_exists('keys_to_camel_case')) {
    /**
     * 将数组key转换为小写驼峰
     *
     * @param array $array 输入数组
     * @param array $keys  要转换的key
     *
     * @return array
     * @变更说明：无需修改，无框架依赖
     */
    function keys_to_camel_case(array $array, array $keys = []): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            // 将键名转换为驼峰命名
            $camelCaseKey = $keys && in_array($key, $keys) ? parse_name($key, 1, false) : $key;

            if (is_array($value)) {
                // 如果值是数组，递归转换
                $result[$camelCaseKey] = keys_to_camel_case($value);
            } else {
                $result[$camelCaseKey] = $value;
            }
        }
        return $result;
    }
}


if (!function_exists('get_account_verification_type')) {

    /**
     * 获取可用的账户验证方式
     * 用于：用户找回密码|用户注册
     * @return string[] email=电子邮件,mobile=手机短信验证
     * @throws Throwable
     */
    function get_account_verification_type(): array
    {
        $types = [];

        // 电子邮件，检查后台系统邮件配置是否全部填写
        $sysMailConfig = get_sys_config('', 'mail');
        $configured    = true;
        foreach ($sysMailConfig as $item) {
            if (!$item) {
                $configured = false;
            }
        }
        if ($configured) {
            $types[] = 'email';
        }

        // 手机号，检查是否安装短信模块
        $sms = Server::getIni(Filesystem::fsFit(root_path() . 'modules/sms/'));
        if ($sms && $sms['state'] == 1) {
            $types[] = 'mobile';
        }

        return $types;
    }
}

if (!function_exists('encrypt_password')) {

    /**
     * 加密密码
     * @deprecated 使用 hash_password 代替
     */
    function encrypt_password($password, $salt = '', $encrypt = 'md5')
    {
        return $encrypt($encrypt($password) . $salt);
    }
}

if (!function_exists('hash_password')) {

    /**
     * 创建密码散列（hash）
     */
    function hash_password(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}

if (!function_exists('verify_password')) {

    /**
     * 验证密码是否和散列值匹配
     * @param string $password 密码
     * @param string $hash     散列值
     * @param array  $extend   扩展数据
     * @noinspection PhpDeprecationInspection
     */
    function verify_password(string $password, string $hash, array $extend = []): bool
    {
        // 第一个表达式直接检查是否为 password_hash 函数创建的 hash 的典型格式，即：$algo$cost$salt.hash
        if (str_starts_with($hash, '$') || password_get_info($hash)['algoName'] != 'unknown') {
            return password_verify($password, $hash);
        } else {
            // 兼容旧版 md5 加密的密码
            return encrypt_password($password, $extend['salt'] ?? '') === $hash;
        }
    }

}
if (!function_exists('get_ba_client')) {
    /**
     * 获取一个请求 BuildAdmin 开源社区的 Client
     * @throws Throwable
     */
    function get_ba_client(): string
    {
        // return new Client([
        //     'base_uri'        =>  config('buildadmin.api_url'),
        //     'timeout'         => 30,
        //     'connect_timeout' => 30,
        //     'verify'          => false,
        //     'http_errors'     => false,
        //     'headers'         => [
        //         'X-REQUESTED-WITH' => 'XMLHttpRequest',
        //         'Referer'          => dirname(request()->root(true)),
        //         'User-Agent'       => 'BuildAdminClient',
        //     ]
        // ]);
        return '';
    }

    if (!function_exists('configSet')) {

        function configSet(array $config, $configs, ?string $name = null): array
        {
            if (empty($name)) {
                return array_merge($configs, array_change_key_case($config));
            }

            if (isset($configs[$name])) {
                $result = array_merge($$configs[$name], $config);
            } else {
                $result = $config;
            }

            $configs[$name] = $result;

            return $result;
        }
    }
}




