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
 * File      XssClear.php
 * Author    albert@rocareer.com
 * Time      2025-04-20 18:14:07
 * Describe  XssClear.php
 */
namespace plugin\radmin\middleware;

use Webman\Http\Request;
use voku\helper\AntiXSS;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class RadminXSSFilterMiddleWare implements MiddlewareInterface
{
	public function process(Request $request, callable $handler): Response
	{
		$antiXSS = new AntiXSS();
		
		// 过滤 GET 数据
		$getData = $request->get();
		foreach ($getData as $key => $value) {
			$getData[$key] = $antiXSS->xss_clean($value);
		}
		$request->setGet($getData);
		
		// 过滤 POST 数据
		$postData = $request->post();
		foreach ($postData as $key => $value) {
			$postData[$key] = $antiXSS->xss_clean($value);
		}
		$request->setPost($postData);
		
		return $handler($request);
	}
}
