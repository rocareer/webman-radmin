<?php


namespace plugin\radmin\support\member;

use plugin\radmin\support\StatusCode;
use Radmin\Container;
use Radmin\exception\BusinessException;
use Radmin\Http;
use Radmin\Log;
use Radmin\orm\Rdb;
use Throwable;

/**
 * 基础状态管理器
 */
class State implements InterfaceState
{


    public bool $login = false;

    /**
     * @var string 用户类型
     */
    public string $role = 'admin';

    /**
     * 缓存前缀
     */
    protected string $cachePrefix = '';

    /**
     * 缓存时间（秒）
     */
    protected array $config = [];

    /**
     * @var string
     */
    protected static string $loginLogTable = '';

    //instance
    protected mixed $memberModel;
    //instance
    protected InterfaceService       $service;
    protected InterfaceAuthenticator $authenticator;
    private mixed                    $context;

    public function __construct()
    {
        $this->context     = Container::get('member.context');
        $this->memberModel = $this->context->get('model');
        $this->config      = config('plugin.radmin.auth');
    }


    /**
     * 检查用户状态
     * By albert  2025/05/06 01:41:07
     * @throws BusinessException
     */
    public function checkStatus($member): bool
    {
        $this->memberModel = $member;
        if ($this->memberModel->status !== 'enable') {
            throw new BusinessException('账号已被禁用', StatusCode::USER_DISABLED, true);
        }
        // 检查登录失败次数
        $this->checkLoginFailures();
        // 检查扩展状态
        $this->checkExtendStatus();
        return true;
    }

    /**
     * 检查登录失败
     * By albert  2025/05/06 00:33:08
     * @return bool
     * @throws BusinessException
     */
    protected function checkLoginFailures(): bool
    {
        if ($this->memberModel->login_failure >= $this->config['login'][$this->role]['login_failure_retry']) {
            $lockTime   = $this->config['login'][$this->role]['login_lock_time'];
            $unlockTime = $this->memberModel->last_login_time + $lockTime;

            if (time() < $unlockTime) {
                throw new BusinessException(
                    "账号已锁定，请在" . ($unlockTime - time()) . "秒后重试",
                    StatusCode::LOGIN_ACCOUNT_LOCKED, true
                );
            }
            $this->memberModel->login_failure = 0;
            $this->memberModel->save();
        }
        return true;
    }

    /**
     * 更新登录信息
     * By albert  2025/05/06 02:50:25
     * @param        $member
     * @param string $success
     * @return bool
     */
    public function updateLoginState($member, string $success): bool
    {
        try {
            $this->memberModel = $member;
            $this->memberModel->startTrans();

            if ($success === 'state.updateLogin.success') {
                $this->memberModel->login_failure = 0;
            } else {
                $this->memberModel->login_failure++;
            }

            $this->memberModel->last_login_time = time();
            $this->memberModel->last_login_ip   = Http::request()->getRealIp();

            $this->memberModel->save();

            // 记录登录日志

            $this->memberModel->commit();
            return true;
        } catch (Throwable $e) {
            $this->memberModel->rollback();
            Log::error('更新登录状态失败：' . $e->getMessage());
            return false;
        }
    }


    protected function recordLoginLog(bool $success): void
    {
        try {
            Rdb::name($this->getLoginLogTable())->insert([
                'user_id'     => $this->memberModel->id,
                'username'    => $this->memberModel->username,
                'ip'          => Http::request()->getRealIp(),
                'user_agent'  => Http::request()->header('user-agent'),
                'success'     => $success,
                'create_time' => time()
            ]);
        } catch (Throwable $e) {
            Log::error('记录登录日志失败：' . $e->getMessage());
        }
    }


    /**
     * 强制下线用户
     * @param int $userId
     * @return bool
     */
    public function forceLogout(int $userId): bool
    {
        try {
            $user = $this->memberModel->find($userId);
            if (!$user) {
                throw new BusinessException('用户不存在', StatusCode::USER_NOT_FOUND);
            }

            $user->refresh_token = null;
            // $user->save();
            // $this->clearState();

            return true;
        } catch (BusinessException $e) {
            throw $e;
        } catch (Throwable) {
            throw new BusinessException('操作失败，请稍后重试', StatusCode::SERVER_ERROR);
        }
    }


    /**
     * 自动检测用户类型
     * @return string
     */
    protected function detectMemberRole(): string
    {
        if (property_exists($this->memberModel, 'role')) {
            return $this->memberModel->role;
        }
        return strtolower(str_replace('StateManager', '', static::class));
    }

    /**
     * 获取缓存键
     * By albert  2025/05/06 04:47:33
     * @return string
     */
    protected function getStateCacheKey(): string
    {
        return $this->cachePrefix . "{$this->memberModel->id}";
    }

    /**
     * 初始化缓存前缀
     * By albert  2025/05/06 04:03:36
     * @return void
     */
    protected function initCachePrefix(): void
    {
        $type              = strtolower(class_basename($this->memberModel));
        $this->cachePrefix = $this->config['state']['prefix'] . "{$type}-";
    }

    /**
     * 检查扩展状态
     * @throws AuthException
     */
    protected function checkExtendStatus(): void
    {
        // 简化状态检查
    }
}
