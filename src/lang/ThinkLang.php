<?php


namespace Radmin\lang;

use Radmin\Request;
use think\facade\Cookie;


/**
 * 多语言管理类
 * @package think
 */
class ThinkLang
{
    /**
     * 配置参数
     * @var array
     */
    protected $config = [
        // 默认语言
        'default_lang'        => 'zh-cn',
        // 自动侦测浏览器语言
        'auto_detect_browser' => true,
        // 允许的语言列表
        'allow_lang_list'     => [],
        // 是否使用Cookie记录
        'use_cookie'          => true,
        // 扩展语言包
        'extend_list'         => [],
        // 多语言cookie变量
        'cookie_var'          => 'think_lang',
        // 多语言header变量
        'header_var'          => 'think-lang',
        // 多语言自动侦测变量名
        'detect_var'          => 'lang',
        // Accept-Language转义为对应语言包名称
        'accept_language'     => [
            'zh-hans-cn' => 'zh-cn',
        ],
        // 是否支持语言分组
        'allow_group'         => false,
    ];

    /**
     * 多语言信息
     * @var array
     */
    private static $lang = [];

    /**
     * 当前语言
     * @var string
     */
    private $range = 'zh-cn';

    /**
     * 构造方法
     * @access public
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = array_merge($this->config, array_change_key_case($config));
        $this->range  = $this->config['default_lang'];

        // 初始化时自动加载默认语言包
        $this->switchLangSet($this->range);
    }

    /**
     * 获取当前语言配置
     * @access public
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * 设置当前语言
     * @access public
     * @param string $lang 语言

     */
    public function setLangSet(string $lang)
    {
        $this->range = $lang;
    }

    /**
     * 获取当前语言
     * @access public
     * @return string
     */
    public function getLangSet(): string
    {
        return $this->range;
    }

    /**
     * 获取默认语言
     * @access public
     * @return string
     */
    public function defaultLangSet()
    {
        return $this->config['default_lang'];
    }

    /**
     * 自动侦测设置获取语言选择
     * @access public
     * @param Request $request
     * @return string
     */
    public function detect(Request $request): string
    {
        // 自动侦测设置获取语言选择
        $langSet = '';

        if ($request->get($this->config['detect_var'])) {
            // URL中设置了语言变量
            $langSet = strtolower($request->get($this->config['detect_var']));
        } elseif ($request->header($this->config['header_var'])) {
            // Header中设置了语言变量
            $langSet = strtolower($request->header($this->config['header_var']));
        } elseif ($request->cookie($this->config['cookie_var'])) {
            // Cookie中设置了语言变量
            $langSet = strtolower($request->cookie($this->config['cookie_var']));
        } elseif ($this->config['auto_detect_browser'] && $request->header('accept-language')) {
            // 自动侦测浏览器语言
            $match = [];
            preg_match('/^([a-z\d\-]+)/i', $request->header('accept-language'), $match);
            $langSet = strtolower($match[1] ?? '');
            if (isset($this->config['accept_language'][$langSet])) {
                $langSet = $this->config['accept_language'][$langSet];
            }
        }

        if (empty($this->config['allow_lang_list']) || in_array($langSet, $this->config['allow_lang_list'])) {
            // 合法的语言
            $this->range = $langSet ?: $this->config['default_lang'];
        }

        // 切换到检测到的语言
        $this->switchLangSet($this->range);

        return $this->range;
    }

    /**
     * 保存当前语言到Cookie
     * @access public

     */
    public function saveToCookie()
    {
        if ($this->config['use_cookie']) {
            Cookie::set($this->config['cookie_var'], $this->range);
        }
    }

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
        $list =  config('plugin.radmin.lang.extend_list', []);
        if (isset($list[$langset])) {
            $files = array_merge($files, (array)$list[$langset]);
        }

        // 加载所有语言文件
        foreach ($files as $file) {
            if (is_file($file)) {
                $this->load($file, $langset);
            }
        }

        // 记录调试信息
        //        Log::debug('Language files loaded for ' . $langset . ': ' . json_encode($files));
        //        Log::debug('Current language data: ' . json_encode(self::$lang[$langset] ?? []));
    }

    /**
     * 加载语言定义(不区分大小写)
     * @access public
     * @param string|array $file  语言文件
     * @param string       $range 语言作用域
     * @return array
     */
    public function load($file, $range = ''): array
    {
        $range = $range ?: $this->range;
        if (!isset(self::$lang[$range])) {
            self::$lang[$range] = [];
        }

        $lang = [];

        foreach ((array) $file as $name) {
            if (is_file($name)) {
                $result = $this->parse($name);
                $lang   = array_change_key_case($result) + $lang;
            }
        }

        if (!empty($lang)) {
            self::$lang[$range] = array_merge(self::$lang[$range], $lang);
            //            Log::debug('Loaded language file: ' . json_encode($file) . ' for range: ' . $range);
        }

        return self::$lang[$range];
    }

    /**
     * 解析语言文件
     * @access protected
     * @param string $file 语言文件名
     * @return array
     */
    protected function parse(string $file): array
    {
        $type   = pathinfo($file, PATHINFO_EXTENSION);
        $result = match ($type) {
            'php'         => include $file,
            'yml', 'yaml' => function_exists('yaml_parse_file') ? yaml_parse_file($file) : [],
            'json'        => json_decode(file_get_contents($file), true),
            default       => [],
        };

        return is_array($result) ? $result : [];
    }

    /**
     * 判断是否存在语言定义(不区分大小写)
     * @access public
     * @param string  $name  语言变量
     * @param string  $range 语言作用域
     * @return bool
     */
    public function has(string $name, string $range = ''): bool
    {
        $range = $range ?: $this->range;

        if ($this->config['allow_group'] && str_contains($name, '.')) {
            [$name1, $name2] = explode('.', $name, 2);
            return isset(self::$lang[$range][strtolower($name1)][$name2]);
        }

        return isset(self::$lang[$range][strtolower($name)]);
    }

    /**
     * 获取语言定义(不区分大小写)
     * @access public
     * @param string|null $name  语言变量
     * @param array       $vars  变量替换
     * @param string      $range 语言作用域
     * @return mixed
     */
    public function get(?string $name = null, array $vars = [], string $range = '')
    {
        $range = $range ?: $this->range;

        // 如果语言包未加载，尝试加载
        if (!isset(self::$lang[$range])) {
            $this->switchLangSet($range);
        }

        // 记录调试信息
        //        Log::debug('Getting language key: ' . $name . ' from range: ' . $range);
        //        Log::debug('Available language data: ' . json_encode(self::$lang[$range] ?? []));

        // 空参数返回所有定义
        if (is_null($name)) {
            return self::$lang[$range] ?? [];
        }

        if ($this->config['allow_group'] && str_contains($name, '.')) {
            [$name1, $name2] = explode('.', $name, 2);
            $value = self::$lang[$range][strtolower($name1)][$name2] ?? $name;
        } else {
            $value = self::$lang[$range][strtolower($name)] ?? $name;
        }

        // 变量解析
        if (!empty($vars) && is_array($vars)) {
            if (key($vars) === 0) {
                // 数字索引解析
                array_unshift($vars, $value);
                $value = call_user_func_array('sprintf', $vars);
            } else {
                // 关联索引解析
                $replace = array_keys($vars);
                foreach ($replace as &$v) {
                    $v = "{:{$v}}";
                }
                $value = str_replace($replace, $vars, $value);
            }
        }

        return $value;
    }
}
