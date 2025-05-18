<?php
/**
 * File:        RadminAuthMiddleware.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/16 05:55
 * Description:
 *
 * Copyright [2014-2026] [https://rocareer.com]
 * Licensed under the Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */

namespace plugin\radmin\middleware;

use plugin\radmin\exception\TokenException;
use plugin\radmin\exception\TokenExpiredException;
use plugin\radmin\exception\UnauthorizedHttpException;
use plugin\radmin\support\member\Member;
use plugin\radmin\support\StatusCode;
use Radmin\Container;
use Radmin\Request;
use Radmin\Response;
use Radmin\token\Token;
use Throwable;

class RadminAuthMiddleware implements MiddlewareInterface
{

    /**
     * 允许访问的角色
     */
    protected string $allowedRole;

    /**
     * 构造函数
     * @param string|null $allowedRole
     */
    public function __construct(?string $allowedRole = null)
    {
        $this->allowedRole = $allowedRole;
    }

    /**
     * @throws Throwable
     */
    public function process(Request $request, callable $handler): Response
    {
        // 0. 设置请求角色
        $request->role = $this->allowedRole;



        // 2. 检查是否跳过认证
        if (shouldExclude($request->path())) return $handler($request);

        // 1. 初始化上下文
        $this->ContextInit();

        // 3. 没有凭证则跳过
        if (empty($request->token)) throw new UnauthorizedHttpException('没有凭证', StatusCode::TOKEN_NOT_FOUND, true);

        // 4. 验证Token有效性 无效则通知刷新
        try {
            $request->payload = Token::verify($request->token);
        } catch (TokenExpiredException) {
            throw new TokenException('', StatusCode::TOKEN_SHOULD_REFRESH);
        } catch (Throwable) {
            throw new UnauthorizedHttpException('凭证无效', StatusCode::TOKEN_INVALID, true);
        }

        // 5. 初始化后 设置 member 信息到请求体
        $request->member = Member::initialization();

        // 6. 验证请求角色
        if (!Member::hasRole($this->allowedRole, $request->member->roles)) throw new TokenException('', StatusCode::TOKEN_SHOULD_REFRESH);

        // 7. 处理请求
        return $handler($request);
    }

    /**
     * @return void
     */
    private function ContextInit(): void
    {
        $context ??= Container::get('member.context');
        $context->set('role', $this->allowedRole);
    }
}
