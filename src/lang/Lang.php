<?php
/**
 * File:        Lang.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/16 21:38
 * Description: 从 think-lang 迁移 适配webman
 *
 * Copyright [2014-2026] [https://rocareer.com]
 * Licensed under the Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */


namespace Radmin\lang;



use Radmin\Request;
use think\Facade;
use think\facade\Cookie;

/**
 * @see ThinkLang
 * @package think\facade
 * @mixin ThinkLang
 * @method static void setLangSet(string $lang) 设置当前语言
 * @method static string getLangSet() 获取当前语言
 * @method static string defaultLangSet() 获取默认语言
 * @method static array load(string|array $file, string $range = '') 加载语言定义(不区分大小写)
 * @method static bool has(string|null $name, string $range = '') 判断是否存在语言定义(不区分大小写)
 * @method static mixed get(string|null $name = null, array $vars = [], string $range = '') 获取语言定义(不区分大小写)
 * @method static string detect(Request $request) 自动侦测设置获取语言选择
 * @method static void saveToCookie(Cookie $cookie) 保存当前语言到Cookie
 */

//todo 保存当前语言到Cookie
class Lang extends Facade
{
	/**
	 * 获取当前Facade对应类名（或者已经绑定的容器对象标识）
	 * @access protected
	 * @return string
	 */
	protected static function getFacadeClass(): string
    {
		return ThinkLang::class;
	}
}
