<?php

namespace app\common\model;

use support\StatusCode;
use exception\AuthException;
use support\Log;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 用户基础模型
 */
abstract class BaseUser extends BaseModel
{

    public mixed     $id;
    public int       $login_failure;
    public int       $last_login_time;
    public string    $last_login_ip;
    public string    $username;
    public string $modelType     = 'user';
    protected string $resultSetType = '\app\common\model\BaseUser';
    /**
     * @var array 状态映射
     */
    protected array $statusMap = [
        'enable'  => '正常',
        'disable' => '禁用'
    ];

    /**
     * @var array 默认角色
     */
    protected array $defaultRoles = [];

    /**
     * @var array 允许输出的字段
     */
    protected array $allowFields = [
        'id',
        'username',
        'nickname',
        'email',
        'mobile',
        'avatar',
        'create_time',
        'update_time'
    ];


    /**
     * 获取允许访问的字段
     * By albert  2025/05/06 01:11:14
     * @return BaseUser
     */
    public function getAllowedInfo(): BaseUser
    {
        // 获取允许访问的字段
        return $this->visible($this->allowFields);
    }


    /**
     * 检查用户状态
     * @throws AuthException
     */
    public function checkStatus(): void
    {
        // 检查基本状态
        if ($this->status !== 'enable') {
            throw new AuthException('账号已被禁用', StatusCode::USER_DISABLED);
        }

        // 检查登录失败次数
        $maxFailures =  config('auth.max_login_failures', 10);
        $lockTime    =  config('auth.login_lock_time', 900);

        if (($this->login_failure ?? 0) >= $maxFailures) {
            $lastLoginTime = $this->last_login_time ?? 0;
            $unlockTime    = $lastLoginTime + $lockTime;

            if (time() < $unlockTime) {
                $remainingTime = $unlockTime - time();
                throw new AuthException(
                    "账号已锁定，请在{$remainingTime}秒后重试",
                    StatusCode::LOGIN_ACCOUNT_LOCKED
                );
            }

            // 锁定时间已过，重置失败次数
            $this->login_failure = 0;
            $this->save();
        }
    }

    /**
     * 更新登录信息
     * @return bool
     */
    public function updateLoginInfo(): bool
    {
        try {
            $this->startTrans();

            $this->last_login_time = time();
            $this->last_login_ip   = request()->getRealIp();
            $this->login_failure   = 0;
            $this->save();

            // 记录登录日志
            $this->recordLoginLog([
                'user_id'  => $this->id,
                'username' => $this->username,
                'success'  => true
            ]);

            $this->commit();
            return true;
        } catch (\Throwable $e) {
            $this->rollback();
            Log::error('更新登录信息失败：' . $e->getMessage());
            return false;
        }
    }

    /**
     * 查找用户
     * By albert  2025/05/06 00:00:31
     * @param string $username
     * @param bool   $withAllowFields
     * @return BaseUser
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function findUserByName(string $username, bool $withAllowFields = true): BaseUser
    {
        $user = $this->where('username', $username)->find();
        if (!$user) {
            throw new AuthException('用户不存在', StatusCode::USER_NOT_FOUND);
        }
        if ($withAllowFields) {
            $user = $user->getAllowedInfo();
        }
        return $user;
    }

    /**
     * 查找用户
     * By albert  2025/05/06 01:13:05
     * @param int  $id
     * @param bool $withAllowFields
     * @return BaseUser
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function findUserById(int $id, bool $withAllowFields = true): BaseUser
    {
        $user = $this->find($id);
        if (!$user) {
            throw new AuthException('用户不存在', StatusCode::USER_NOT_FOUND);
        }
        if ($withAllowFields) {
            $user = $user->getAllowedInfo();
        }
        return $user;
    }

    /**
     * 查找并验证用户
     * By albert  2025/05/06 00:01:06
     * @param string $username
     * @param string $password
     * @return static
     */
    public static function findAndValidate(string $username, string $password): static
    {
        // 查找用户
        $user = static::where('username', $username);
        if (!$user) {
            throw new AuthException('用户不存在', StatusCode::USER_NOT_FOUND);
        }
        // 验证密码
        if (!$user->verifyPassword($password, $user)) {
            throw new AuthException('密码错误', StatusCode::PASSWORD_ERROR);
        }

        // 确保返回的是BaseUser对象
        $user = $user->getAllowedInfo();

        return $user;
    }

    /**
     * 验证密码
     * @param string $inputPassword 用户输入的密码
     * @param mixed  $userPassword  数据库中存储的密码hash
     * @return bool
     */
    public function verifyPassword(string $inputPassword, $userPassword): bool
    {
        // 处理对象形式的用户密码
        $hash = is_object($userPassword) ? $userPassword->password : $userPassword;

        // 处理数组形式的用户密码
        if (is_array($userPassword)) {
            $hash = $userPassword['password'] ?? $userPassword['password_hash'] ?? '';
        }

        // 获取salt等额外参数
        $extend = [];
        if (is_object($userPassword) && property_exists($userPassword, 'salt')) {
            $extend['salt'] = $userPassword->salt;
        } elseif (is_array($userPassword) && isset($userPassword['salt'])) {
            $extend['salt'] = $userPassword['salt'];
        }
        // 调用公共密码验证函数
        return verify_password($inputPassword, $hash, $extend);
    }


    /**
     * 记录登录失败
     * @param string|null $reason 失败原因
     * @return bool
     */
    public function recordLoginFailure(?string $reason = null): bool
    {
        try {
            $this->startTrans();

            $this->login_failure   = ($this->login_failure ?? 0) + 1;
            $this->last_login_time = time();
            $this->last_login_ip   = request()->getRealIp();
            $this->save();

            // 记录登录日志
            $this->recordLoginLog([
                'user_id'  => $this->id,
                'username' => $this->username,
                'success'  => false,
                'message'  => $reason
            ]);

            $this->commit();
            return true;
        } catch (\Throwable $e) {
            $this->rollback();
            Log::error('记录登录失败信息失败：' . $e->getMessage());
            return false;
        }
    }


    /**
     * 修改密码
     * @param string      $newPassword
     * @param string|null $oldPassword 如果提供旧密码，则验证旧密码
     * @return bool
     * @throws AuthException
     */
    public function changePassword(string $newPassword, ?string $oldPassword = null): bool
    {
        try {
            // 如果提供了旧密码，验证旧密码
            if ($oldPassword !== null && !$this->verifyPassword($oldPassword, $this)) {
                throw new AuthException('原密码错误', StatusCode::PASSWORD_ERROR);
            }

            $this->startTrans();

            // 更新密码
            $this->password = password_hash($newPassword, PASSWORD_DEFAULT);
            $result         = $this->save();

            // 记录密码修改日志
            $this->recordPasswordChange();

            $this->commit();
            return $result;
        } catch (AuthException $e) {
            $this->rollback();
            throw $e;
        } catch (\Throwable $e) {
            $this->rollback();
            Log::error('修改密码失败：' . $e->getMessage());
            throw new AuthException('修改密码失败', StatusCode::PASSWORD_CHANGE_FAILED);
        }
    }

    /**
     * 记录登录日志
     * @param array $data 日志数据
     */
    protected function recordLoginLog(array $data): void
    {
        try {
            $log = [
                'user_id'     => $data['user_id'] ?? 0,
                'username'    => $data['username'] ?? '',
                'ip'          => request()->getRealIp(),
                'success'     => $data['success'] ?? 0,
                'message'     => $data['message'] ?? '',
                'user_agent'  => request()->header('user-agent'),
                'create_time' => time()
            ];

            \support\think\Db::name($this->getLoginLogTable())->insert($log);
        } catch (\Throwable $e) {
            Log::error('记录登录日志失败：' . $e->getMessage());
        }
    }

    /**
     * 记录密码修改日志
     */
    protected function recordPasswordChange(): void
    {
        try {
            $log = [
                'user_id'     => $this->id,
                'username'    => $this->username,
                'ip'          => request()->getRealIp(),
                'user_agent'  => request()->header('user-agent'),
                'create_time' => time()
            ];

            \support\think\Db::name($this->getPasswordLogTable())->insert($log);
        } catch (\Throwable $e) {
            Log::error('记录密码修改日志失败：' . $e->getMessage());
        }
    }

    /**
     * 获取登录日志表名
     * @return string
     */
    protected function getLoginLogTable(): string
    {
        return 'user_login_log';
    }

    /**
     * 获取密码修改日志表名
     * @return string
     */
    protected function getPasswordLogTable(): string
    {
        return 'user_password_log';
    }

    /**
     * 获取用户角色
     * @return array
     */
    public function getRoles(): array
    {
        return $this->defaultRoles;
    }


    /**
     * 检查是否有指定角色
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return in_array($role, $this->getRoles());
    }

    /**
     * 获取用户权限
     * @return array
     */
    abstract public function getPermissions(): array;

    /**
     * 检查是否有指定权限
     * @param string $permission
     * @return bool
     */
    abstract public function hasPermission(string $permission): bool;

    /**
     * 获取用户菜单
     * @return array
     */
    abstract public function getMenus(): array;
}
