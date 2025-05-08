<?php

namespace middleware;


use service\member\state\State;
use support\member\Member;
use support\StatusCode;
use support\token\Token;
use Rocareer\Radmin\Exception\BusinessException;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class RadminAuthMiddleware implements MiddlewareInterface
{

    /**
     * 允许访问的角色
     * @var array
     */
    protected array $allowedRoles = [];

    /**
     * 构造函数
     * @param array $allowedRoles 允许访问的角色列表
     */
    public function __construct(array $allowedRoles = [])
    {
        $this->allowedRoles = $allowedRoles;
    }

    /**
     * @throws \Throwable
     */
    public function process(Request $request, callable $handler): Response
    {

        // 1. 检查是否跳过认证
        if (shouldExclude($request->path())) {
            return $handler($request);
        }

        // 2. 没有凭证则跳过
        if (empty($request->token)) {
            return $handler($request);
        }
        // 3. 验证Token有效性
        $payload = Token::verify($request->token);
        if (!$payload) {
            throw new BusinessException('凭证无效:请重新登录', StatusCode::TOKEN_INVALID, ['type' => 'need login']);
        }



        // 6. 设置 member 信息到请求对象
        $request->member =Member::initialization();;
        $request->roles  =$request->member->roles;

        return $handler($request);

    }
}
