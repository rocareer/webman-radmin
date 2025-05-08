<?php

namespace plugin\radmin\support\member;

use plugin\radmin\support\StatusCode;
use plugin\radmin\support\token\Token;
use Rocareer\Radmin\Exception\BusinessException;
use Rocareer\Radmin\Exception\UnauthorizedHttpException;
use support\Log;
use support\think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use Webman\Event\Event;

/**
 * 认证器抽象基类
 */
abstract class Authenticator implements InterfaceAuthenticator
{
    /**
     * @var string 用户模型类型
     */
    protected string $role = 'user';

    /**
     * @var array 登录凭证
     */
    protected array $credentials;

    /**
     * @var array
     */
    protected array $config = [];

    protected object|null $memberModel =null;
    //instance

    public function __construct()
    {
        $this->config       = radmin_config('auth.login.' . $this->role);
        $this->memberModel=Factory::getInstance($this->role,'model');
    }

    /**
     * 认证用户
     * By albert  2025/05/06 01:49:43
     * @param array $credentials
     * @return object
     * @throws UnauthorizedHttpException
     */
    public function authenticate(array $credentials): object
    {
        $this->credentials = $credentials;

        try {
            Db::startTrans();

            // 1. 验证基本凭证
            $this->validateCredentials();

            // 2. 验证验证码
            $this->validateCaptcha();


            // 3. 验证用户
            $this->findMember();

            // 5. 检查用户状态
            $this->checkMemberStatus();

            // 6. 验证密码
            $this->verifyPassword();

            // 7. 生成令牌
            $this->generateTokens();

            // 8. 更新登录状态
            $this->updateLoginState('success');

            // 9. 附加最终输出信息
            $this->extendMemberInfo();

            // 9. 缓存用户状态
            // $this->cacheState();


            // // 9. 记录操作日志
            // $this->recordOperationLog([
            //     'action'      => 'login',
            //     'description' => '用户登录',
            //     'type'        => $this->role,
            //     'data'        => [
            //         'ip'   => request()->getRealIp(),
            //         'keep' => !empty($credentials['keep'])
            //     ]
            // ]);

            Db::commit();
            return $this->memberModel;

        } catch (UnauthorizedHttpException $e) {
            Db::rollback();
            $this->updateLoginState('false');
            Db::commit();
            throw new UnauthorizedHttpException($e->getMessage(), StatusCode::AUTHENTICATION_FAILED);
        } catch (\Throwable $e) {
            Db::rollback();
            $this->updateLoginState('false');
            Db::commit();
            Log::error('认证异常：' . $e->getMessage());
            throw new UnauthorizedHttpException($e->getMessage(), StatusCode::AUTHENTICATION_FAILED);
        }
    }

    /**
     * 验证基本凭证
     * By albert  2025/05/06 01:51:31
     * @return void
     */
    protected function validateCredentials(): void
    {
        if (empty($this->credentials['username'])) {
            throw new UnauthorizedHttpException('用户名不能为空', StatusCode::USERNAME_REQUIRED);
        }

        if (empty($this->credentials['password'])) {
            throw new UnauthorizedHttpException('密码不能为空', StatusCode::PASSWORD_REQUIRED);
        }
    }

    /**
     * 验证验证码
     * By albert  2025/05/06 04:00:32
     * @return void
     * @throws UnauthorizedHttpException
     */
    protected function validateCaptcha(): void
    {
        if ($this->config['captcha'] && empty($this->credentials['captcha'])) {
            throw new UnauthorizedHttpException('验证码不能为空', StatusCode::CAPTCHA_REQUIRED);
        }
    }

    /**
     * 获取用户
     * By albert  2025/05/06 04:00:02
     * @return void
     * @throws DataNotFoundException
     * @throws DbException
     * @throws UnauthorizedHttpException
     */
    protected function findMember(): void
    {
        $user = $this->memberModel->findByName($this->credentials['username']);
        if (!$user) {
            throw new UnauthorizedHttpException('用户不存在', StatusCode::USER_NOT_FOUND);
        }

        $this->memberModel = $user;

    }

    /**
     * 检查用户状态
     */
    protected function checkMemberStatus(): void
    {
        Event::dispatch('state.checkStatus',$this->memberModel);
    }

    /**
     * 验证密码
     * By albert  2025/05/06 01:33:02
     * @return void
     * @throws UnauthorizedHttpException
     */
    protected function verifyPassword(): void
    {
        $res = $this->memberModel->verifyPassword($this->credentials['password'], $this->memberModel);
        if (!$res) {
            throw new UnauthorizedHttpException('密码错误', StatusCode::PASSWORD_ERROR);
        }
    }


    /**
     * 生成令牌
     * By albert  2025/05/06 02:06:25
     * @return void
     * @throws UnauthorizedHttpException
     */
    protected function generateTokens(): void
    {

        try {

            $tokenData                = [
                'user_id'  => $this->memberModel->id,
                'username' => $this->memberModel->username,
                'type'     => $this->role
            ];
            $this->memberModel->token = Token::encode($tokenData);

            if (empty($this->memberModel->token)) {
                throw new UnauthorizedHttpException('获取凭证失败', StatusCode::TOKEN_CREATE_FAILED);
            }// 获取刷新秘钥
            if ($this->credentials['keep']) {
                $this->memberModel->refresh_token = Token::encode($tokenData, true);
                if (empty($this->memberModel->refresh_token)) {
                    throw new UnauthorizedHttpException('获取凭证失败', StatusCode::TOKEN_CREATE_FAILED);
                }
            }
        } catch (\Throwable $e) {
            throw new UnauthorizedHttpException('获取凭证失败', StatusCode::TOKEN_CREATE_FAILED);
        }
    }

    /**
     * 更新登录状态
     * @param bool $success
     */
    protected function updateLoginState(string $success): void
    {
        Event::emit("state.updateLogin.$success",$this->memberModel);
    }


    /**
     * 记录操作日志
     * @param array $logData 日志数据
     */
    protected function recordOperationLog(array $logData): void
    {
        if ($this->stateManager && method_exists($this->stateManager, 'recordOperationLog')) {
            $this->stateManager->recordOperationLog(
                $logData['action'] ?? 'operation',
                $logData['type'] ?? 'common',
                $logData['description'] ?? '',
                $logData['data'] ?? []
            );
        }
    }

    /**
     * 刷新认证令牌
     * @param string $refreshToken
     * @return array
     * @throws UnauthorizedHttpException|BusinessException
     */
    public function refreshToken(string $refreshToken): string
    {
        try {
            Db::startTrans();

            // 验证用户
            $this->validateRefreshToken($refreshToken);

            // 检查状态
            $this->checkMemberStatus();

            // 生成新令牌
            $this->generateTokens();

            // 记录操作日志
            $this->recordOperationLog([
                'action'      => 'refresh_token',
                'description' => '刷新认证令牌',
                'data'        => ['ip' => request()->getRealIp()]
            ]);

            Db::commit();
            return $this->memberModel->token;

        } catch (\Throwable $e) {
            Db::rollback();
            Log::error('刷新令牌失败', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new UnauthorizedHttpException('系统错误，请稍后重试', StatusCode::SERVER_ERROR);
        }
    }

    /**
     * 验证刷新令牌
     * @param string $refreshToken
     * @return void
     * @throws UnauthorizedHttpException
     */
    protected function validateRefreshToken(string $refreshToken): void
    {
        $payload = Token::verify($refreshToken);
        if (empty($payload['user_id'])) {
            throw new UnauthorizedHttpException('无效的刷新令牌', StatusCode::TOKEN_INVALID, true);
        }

        $user = $this->memberModel->findById($payload['user_id']);
        if (!$user) {
            throw new UnauthorizedHttpException('用户不存在', StatusCode::USER_NOT_FOUND, true);
        }
    }

    /**
     * 注销登录
     * @return bool
     * @throws BusinessException
     */
    public function logout(): bool
    {
        try {
            if (!$this->memberModel) {
                return true;
            }

            // 清除用户状态
            $this->memberModel->refresh_token = null;
            $this->memberModel->save();

            // 清除缓存
            $this->stateManager->clearCache();

            // 记录操作日志
            $this->recordOperationLog([
                'action'      => 'logout',
                'description' => '用户注销',
                'data'        => ['ip' => request()->getRealIp()]
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::error('注销失败', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new BusinessException('注销失败，请稍后重试', StatusCode::SERVER_ERROR);
        }
    }


    /**
     * 强制下线用户
     * @param int $userId
     * @return bool
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws UnauthorizedHttpException
     */
    public function forceLogout(int $userId): bool
    {
        $user = $this->memberModel->find($userId);
        if (!$user) {
            throw new UnauthorizedHttpException('用户不存在', StatusCode::USER_NOT_FOUND);
        }

        $stateManager = new $this->stateManager($user);
        $result       = $stateManager->forceLogout($userId);

        // 记录操作日志
        if ($this->memberModel) {
            $this->recordOperationLog([
                'action'      => 'force_logout',
                'description' => '强制下线用户',
                'data'        => [
                    'target_user_id' => $userId,
                    'ip'             => request()->getRealIp()
                ]
            ]);
        }

        return $result;
    }

}
