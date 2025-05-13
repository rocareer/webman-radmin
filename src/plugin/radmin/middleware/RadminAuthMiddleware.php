<?php

namespace plugin\radmin\app\middleware;


use plugin\radmin\exception\BusinessException;
use plugin\radmin\exception\TokenException;
use plugin\radmin\exception\TokenExpiredException;
use plugin\radmin\exception\UnauthorizedHttpException;
use http\Exception\UnexpectedValueException;
use plugin\radmin\support\member\Member;
use plugin\radmin\support\StatusCode;
use plugin\radmin\support\token\Token;
use Throwable;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class RadminAuthMiddleware implements MiddlewareInterface
{

    /**
     * 允许访问的角色
     * @var array
     */
    protected string|null $allowedRole = null;

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
    public function process(\support\Request|Request $request, callable $handler): Response
    {


        // 1. 检查是否跳过认证
        if (shouldExclude($request->path())) {
            return $handler($request);
        }// 2. 没有凭证则跳过
        if (empty($request->token)) {
            return $handler($request);
        }// 3. 验证Token有效性 无效则通知刷新
        try {
            $request->payload = Token::verify($request->token);
        } catch (TokenExpiredException) {
            throw new TokenException('', StatusCode::TOKEN_SHOULD_REFRESH);
        } catch (Throwable) {
            throw new UnauthorizedHttpException('凭证无效', StatusCode::TOKEN_INVALID, true);
        }// 4. 初始化后 设置 member 信息到请求体
        $request->member = Member::setCurrentRole($this->allowedRole)->initialization();// 5. 验证请求角色
        if (!Member::hasRole($this->allowedRole, $request->member->roles)) {
            throw new TokenException('', StatusCode::TOKEN_SHOULD_REFRESH);
        }//6. 通过
        $request->role = $this->allowedRole;

        return $handler($request);

    }
}
