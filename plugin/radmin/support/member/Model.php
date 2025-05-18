<?php


namespace plugin\radmin\support\member;


use plugin\radmin\support\StatusCode;
use Radmin\exception\BusinessException;
use Radmin\exception\UnauthorizedHttpException;
use Radmin\Http;
use Radmin\Log;
use Radmin\orm\Model as ThinkModel;
use Radmin\orm\Rdb;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use Throwable;

/**
 * 用户基础模型
 * @property mixed  $status
 * @property int    $login_failure
 * @property int    $last_login_time
 * @property string $last_login_ip
 * @property mixed  $id
 * @property mixed  $username
 * @property string $password
 */


abstract class Model extends ThinkModel implements InterfaceModel
{
    /**
     * @var string 当前模型角色
     */
    public string $role = 'admin';
    protected     $model;
    protected ?int      $id =0;
    protected ?string   $username ='';
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
    protected array $allowFields = [];


    protected array $type = [
        'create_time'     => 'integer',
        'update_time'     => 'integer',
        'last_login_time' => 'integer',
        'login_failure'   => 'integer',
    ];



    /**
     *
     * By albert  2025/05/06 16:34:17
     * @param int  $id
     * @param bool $withAllowFields
     * @return object
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function findById(int $id = 0, bool $withAllowFields = true): object
    {
        $this->model = $this->find($id);
        if ($withAllowFields) {
            $this->model = $this->model->visible($this->allowFields);
        }
        return $this->model;
    }

    /**
     * 查找用户
     * By albert  2025/05/06 00:00:31
     * @param string $username
     * @param bool   $withAllowFields
     * @return Model
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function findByName(string $username, bool $withAllowFields = true): mixed
    {
        $member = $this->where('username', $username)->find();
        if (!$member) {
            throw new UnauthorizedHttpException('用户不存在', StatusCode::USER_NOT_FOUND);
        }
        if ($withAllowFields) {
            return $member->visible($this->allowFields);
        }
        return $member;
    }

    /**
     * 获取允许访问的字段
     * By albert  2025/05/06 01:11:14
     * @return object
     */
    public function getAllowedInfo(): object
    {
        // 获取允许访问的字段
        return $this->visible($this->allowFields);
    }


    /**
     * 检查用户状态
     * @throws BusinessException
     */
    public function checkStatus(): void
    {
        // 检查基本状态
        if ($this->status !== 'enable') {
            throw new BusinessException('账号已被禁用', StatusCode::USER_DISABLED);
        }

        // 检查登录失败次数
        $maxFailures = config('plugin.radmin.auth.max_login_failures', 10);
        $lockTime    = config('plugin.radmin.auth.login_lock_time', 900);

        if (($this->login_failure ?? 0) >= $maxFailures) {
            $lastLoginTime = $this->last_login_time ?? 0;
            $unlockTime    = $lastLoginTime + $lockTime;

            if (time() < $unlockTime) {
                $remainingTime = $unlockTime - time();
                throw new BusinessException(
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
     * 查找并验证用户
     * By albert  2025/05/06 00:01:06
     * @param string $username
     * @param string $password
     * @return static
     * @throws BusinessException
     */
    public function findAndValidate(string $username, string $password): static
    {
        // 查找用户
        $this->where('username', $username);
        if (empty($this)) {
            throw new BusinessException('用户不存在', StatusCode::USER_NOT_FOUND);
        }
        // 验证密码
        if (!$this->verifyPassword($password, $this)) {
            throw new BusinessException('密码错误', StatusCode::PASSWORD_ERROR);
        }

        // 确保返回的是BaseUser对象
        $this->visible($this->allowFields);

        return $this;
    }

    /**
     * 验证密码
     * @param string $inputPassword 用户输入的密码
     * @param        $member
     * @return bool
     */
    public function verifyPassword(string $inputPassword, $member): bool
    {
        // 处理对象形式的用户密码
        $hash = is_object($member) ? $member->password : $member;

        // 处理数组形式的用户密码
        if (is_array($member)) {
            $hash = $member['password'] ?? $member['password_hash'] ?? '';
        }

        // 获取salt等额外参数
        $extend = [];
        if (is_object($member) && property_exists($member, 'salt')) {
            $extend['salt'] = $member->salt;
        } elseif (is_array($member) && isset($member['salt'])) {
            $extend['salt'] = $member['salt'];
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
            $this->last_login_ip   = Http::request()->getRealIp();
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
        } catch (Throwable $e) {
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
     * @throws BusinessException
     */
    public function changePassword(string $newPassword, ?string $oldPassword = null): bool
    {
        try {
            // 如果提供了旧密码，验证旧密码
            if ($oldPassword !== null && !$this->verifyPassword($oldPassword, $this)) {
                throw new BusinessException('原密码错误', StatusCode::PASSWORD_ERROR);
            }

            $this->startTrans();

            // 更新密码
            $this->password = password_hash($newPassword, PASSWORD_DEFAULT);
            $result         = $this->save();

            // 记录密码修改日志
            $this->recordPasswordChange();

            $this->commit();
            return $result;
        } catch (BusinessException $e) {
            $this->rollback();
            throw $e;
        } catch (Throwable $e) {
            $this->rollback();
            Log::error('修改密码失败：' . $e->getMessage());
            throw new BusinessException('修改密码失败', StatusCode::PASSWORD_CHANGE_FAILED);
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
                'ip'          => Http::request()->getRealIp(),
                'success'     => $data['success'] ?? 0,
                'message'     => $data['message'] ?? '',
                'user_agent'  => Http::request()->header('user-agent'),
                'create_time' => time()
            ];

            Rdb::name($this->getLoginLogTable())->insert($log);
        } catch (Throwable $e) {
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
                'ip'          => Http::request()->getRealIp(),
                'user_agent'  => Http::request()->header('user-agent'),
                'create_time' => time()
            ];

            Rdb::name($this->getPasswordLogTable())->insert($log);
        } catch (Throwable $e) {
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

}
