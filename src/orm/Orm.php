<?php


namespace Radmin\orm;

use Radmin\cache\Cache;
use Radmin\Http;
use think\Paginator;
use Webman\Bootstrap;

class Orm implements Bootstrap
{
	/**
	 * @var bool
	 */
	private static bool $initialized = false;
	
	/**
	 
	 */
	public static function start($worker)
	{
		if (self::$initialized) {
			return;
		}
		self::$initialized = true;
		
		$config = config('plugin.radmin.think-orm', config('plugin.radmin.thinkorm'));
		if (!$config) {
			return;
		}
		// Container::getInstance()->bind('Rdbm', Rdbm::class);
		// 配置
		Rdb::setConfig($config);

		if (class_exists(Cache::class)) {
			Rdb::setCache(Cache::store());
		}
		
		Paginator::currentPageResolver(function ($pageName = 'page') {
			$request = Http::request();
			if (!$request) {
				return 1;
			}
			$page = $request->input($pageName, 1);
			if (filter_var($page, FILTER_VALIDATE_INT) !== false && (int)$page >= 1) {
				return (int)$page;
			}
			return 1;
		});
		
		// 设置分页url中域名与参数之间的path字符串
		Paginator::currentPathResolver(function () {
			$request = Http::request();
			return $request ? $request->path() : '/';
		});
	}
}
