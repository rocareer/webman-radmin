<?php



namespace plugin\radmin\support\rocareer;



use Radmin\lang\ThinkLang;
use think\Facade;

/**
 * @see ThinkLang
 * @package think\facade
 * @mixin ThinkLang
 * @method static void processInit(int $workerId) 初始化
 * @method static void spiderInit(int $spiderId) 处理数据
 * @method static setProcess(int $spiderId,int $workerId)
 */

//todo 保存当前语言到Cookie
class Spider extends Facade
{
	/**
	 * 获取当前Facade对应类名（或者已经绑定的容器对象标识）
	 * @access protected
	 * @return string
	 */
	protected static function getFacadeClass()
	{
		return SpiderService::class;
	}
}
