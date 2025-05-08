<?php

namespace plugin\radmin\support\member;

use Exception;
use Firebase\JWT\ExpiredException;
use plugin\radmin\service\member\TokenException;
use plugin\radmin\support\StatusCode;
use plugin\radmin\support\token\Token;
use Rocareer\Radmin\Exception\BusinessException;
use Throwable;
use Webman\Event\Event;

abstract class Service implements InterfaceService
{

    //common
    protected InterfaceService $service;
    protected string           $role = 'admin';

    protected string $error = '';

    public ?int $id = null;

    public ?string $username = null;
    //state
    public bool $isLogin = false;

    //request
    public ?string    $token;
    protected ?string $app;

    //instance
    protected object                 $memberModel;
    protected InterfaceAuthenticator $authenticator;

    public function __construct()
    {

        $this->memberModel   = Factory::getInstance($this->role, 'model');
        $this->authenticator = Factory::getInstance($this->role, 'authenticator');
        // $this->stateManager  = Container::get($this->role,'state');
    }


    /**
     * @throws BusinessException
     */
    public function initialization()
    {
        $request = request();
        if (!empty($request->member)) {
            //状态初始化
            $this->memberModel = $request->member;
            //状态更新
            $this->stateUpdateLogin('success');
            return $request->member;
        }

        try {
            if ($this->memberModel && $this->isLogin()) {
                //状态更新
                $this->stateUpdateLogin('success');
                return $this->memberModel;
            }

            //用户信息初始化
            $this->memberInitialization();
            //附加用户信息
            $this->extendMemberInfo();
            //用户状态检查
            $this->stateCheckStatus();
            //缓存用户信息
            //更新登录状态
            $this->stateUpdateLogin('success');

            return $this->memberModel;

        } catch (Exception $e) {
            //清除状态
            //状态更新
            $this->stateUpdateLogin('false');
            throw new BusinessException($e->getMessage(), $e->getCode());
        }
    }


    /**
     * 状态:检查状态
     * By albert  2025/05/06 19:24:35
     */
    protected function stateCheckStatus(): void
    {
        Event::dispatch('state.checkStatus', $this->memberModel);
    }

    /**
     * 状态:更新登录记录
     * By albert  2025/05/06 19:23:20
     * @param string $success
     * @return void
     */
    protected function stateUpdateLogin(string $success): void
    {
        Event::emit("state.updateLogin.$success", $this->memberModel);
    }


    /**
     * 登录 todo 参数修剪
     * By albert  2025/05/06 17:37:22
     * @param array  $credentials
     * @param string $role
     * @param bool   $keep
     * @return array
     */
    public function login(array $credentials, string $role, bool $keep = false): array
    {
        $credentials['keep'] = $keep;
        $this->memberModel   = $this->authenticator->authenticate($credentials);
        //设置用户信息
        $this->setMember($this->memberModel);
        return $this->memberModel->toArray();
    }

    /**
     *
     * By albert  2025/05/08 04:28:10
     * @return bool
     * @throws BusinessException
     */
    public function logout(): bool
    {
        if (!$this->isLogin()) {
            throw new BusinessException('你已退出登录', StatusCode::NEED_LOGIN, ['type' => 'need login']);
        }
        if (request()->token) {
            Token::destroy(request()->token);
        }
        $this->resetMember();
        return true;
    }

    public function register()
    {

    }

    public function resetPassword()
    {

    }

    public function changePassword()
    {

    }

    /**
     * 用户:用户信息初始化
     * By albert  2025/05/06 17:35:46
     * @param string|null $token
     * @throws BusinessException
     */
    public function memberInitialization(?string $token = null): void
    {
        try {
            $token = $token ?? $this->token ?? getTokenFromRequest() ?? false;
            if (empty($token)) {
                throw new BusinessException('没有凭证', StatusCode::TOKEN_NOT_FOUND);
            }
            $tokenData = Token::verify($token);
            if (empty($tokenData)) {
                throw new BusinessException('凭证无效', StatusCode::TOKEN_INVALID);
            }
            $member = $this->memberModel->findById($tokenData->user_id);
            if (empty($member)) {
                throw new BusinessException('用户不存在', StatusCode::MEMBER_NOT_FOUND);
            }
            $this->setMember($member);
        } catch (ExpiredException $e) {
            throw new TokenException('凭证已过期', StatusCode::TOKEN_EXPIRED, ['type' => 'expired']);
        } catch (Throwable $e) {
            throw new BusinessException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * 用户:获取当前登录用户信息
     * By albert  2025/05/06 19:32:38
     * @return object
     */
    public function getMember(): ?object
    {
        return $this->memberModel;
    }


    /**
     * 判断是否登录
     * By albert  2025/05/06 18:40:23
     * @return bool
     */
    public function isLogin(): bool
    {
        return $this->isLogin;
    }


    /**
     * 检查当前用户是否拥有指定角色
     * By albert  2025/04/30 04:02:04
     *
     * @param $role
     *
     * @return bool
     */
    public function hasRole($role, $roles = null): bool
    {
        $payloadRoles = $roles ?? request()->roles;
        return in_array($role, $payloadRoles);
    }

    /**
     * 设置用户信息
     * By albert  2025/05/06 17:48:07
     * @param $member
     * @return void
     */
    public function setMember($member): void
    {
        $this->memberModel = $member;
        $this->memberModel = $member;
        $this->id          = $member->id;
        $this->username    = $member->username;
        $this->isLogin     = true;
    }

    /**
     * 重置用户信息
     * By albert  2025/05/06 17:48:20
     * @return void
     */
    public function resetMember(): void
    {
        $this->memberModel = null;
        $this->id          = null;
        $this->username    = null;
        $this->isLogin     = false;

        // if (empty($this->state)) {
        //     $this->stateInitialization();
        // }
        // $this->stateManager->clearState();
    }


    /**
     * 设置错误消息
     * @param $error
     * @return Service
     */
    public function setError($error): Service
    {
        $this->error = $error;
        return $this;
    }

    /**
     * 获取错误消息
     * @return string
     */
    public function getError(): string
    {
        return $this->error ? __($this->error) : '';
    }


}