<?php


namespace support\member;

use Exception;
use exception\UnauthorizedHttpException;
use support\StatusCode;
use support\think\Db;
use support\token\Token;
use exception\BusinessException;
use Throwable;
use Webman\Event\Event;

abstract class Service implements InterfaceService
{
    //common
    protected InterfaceAuthenticator $authenticator;
    protected InterfaceService       $service;
    protected string                 $role     = 'admin';
    protected string                 $error    = '';
    public ?int                      $id       = null;
    public ?string                   $username = null;
    //state
    public bool $isLogin = false;

    protected mixed $children = null;

    //instance
    protected ?object $memberModel = null;


    /**
     * @throws BusinessException
     */
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
     * @param array $credentials
     * @param bool  $keep
     * @return array
     * @throws BusinessException
     * @throws Throwable
     */
    public function login(array $credentials, bool $keep = false): array
    {
        $credentials['keep'] = $keep;
        $this->memberModel = $this->authenticator->authenticate($credentials);
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
            $token = $token ?? request()->token ?? getTokenFromRequest() ?? false;
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

            $this->memberModel=$member;

        } catch (Throwable $e) {
            throw new BusinessException($e->getMessage(), $e->getCode());
        }
    }

    public function extendMemberInfo(): void
    {
        $this->memberModel->roles=[$this->role];
    }

    /**
     * 用户:获取当前登录用户信息
     * By albert  2025/05/06 19:32:38
     * @return object|null
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
     * @param      $role
     * @param null $roles
     * @return bool
     */
    public function hasRole($role, $roles = null): bool
    {
        $payloadRoles = $roles ?? request()->member->roles??[];
        return in_array($role, $payloadRoles);
    }

    /**
     * 获取菜单规则列表
     * @access public
     * @param int $uid 用户ID
     * @return array
     * @throws Throwable
     */
    public function getMenus(?int $uid = null): array
    {
        $uid             = $uid ?? $this->id;
        $this->children  = [];
        $originAuthRules = $this->getOriginAuthRules($uid);
        foreach ($originAuthRules as $rule) {
            $this->children[$rule['pid']][] = $rule;
        }

        // 没有根菜单规则
        if (!isset($this->children[0])) return [];

        return $this->getChildren($this->children[0]);
    }

    /**
     * 获得权限规则原始数据
     * @param int|null $uid 用户id
     * @return array
     * @throws Throwable
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getOriginAuthRules(?int $uid = null): array
    {
        $uid = $uid ?? $this->id;
        $ids = $this->getRuleIds($uid);
        if (empty($ids)) return [];

        $where   = [];
        $where[] = ['status', '=', '1'];
        // 如果没有 * 则只获取用户拥有的规则
        if (!in_array('*', $ids)) {
            $where[] = ['id', 'in', $ids];
        }
        $rules = Db::name($this->config['auth_rule'])
            ->withoutField(['remark', 'status', 'weigh', 'update_time', 'create_time'])
            ->where($where)
            ->order('weigh desc,id asc')
            ->select()
            ->toArray();
        foreach ($rules as $key => $rule) {
            if (!empty($rule['keepalive'])) {
                $rules[$key]['keepalive'] = $rule['name'];
            }
        }

        return $rules;
    }


    /**
     * 获取权限规则ids
     * @param int $uid
     * @return array
     * @throws Throwable
     */
    public function getRuleIds(?int $id = null): array
    {
        $id = $id ?? $this->id;
        // 用户的组别和规则ID
        $groups = $this->getGroups($id);
        $ids    = [];
        foreach ($groups as $g) {
            $ids = array_merge($ids, explode(',', trim($g['rules'], ',')));
        }
        return array_unique($ids);
    }


    /**
     * 获取用户所有分组和对应权限规则
     * @param int $uid
     * @return array
     * @throws Throwable
     */
    public function getGroups(?int $id = null): array
    {
        $id = $id ?? $this->id;

        $dbName = $this->config['auth_group_access'] ?: 'user';
        if ($this->config['auth_group_access']) {
            $userGroups = Db::name($dbName)
                ->alias('aga')
                ->join($this->config['auth_group'] . ' ag', 'aga.group_id = ag.id', 'LEFT')
                ->field('aga.uid,aga.group_id,ag.id,ag.pid,ag.name,ag.rules')
                ->where("aga.uid='$id' and ag.status='1'")
                ->select()
                ->toArray();
        } else {
            $userGroups = Db::name($dbName)
                ->alias('u')
                ->join($this->config['auth_group'] . ' ag', 'u.group_id = ag.id', 'LEFT')
                ->field('u.id as uid,u.group_id,ag.id,ag.name,ag.rules')
                ->where("u.id='$id' and ag.status='1'")
                ->select()
                ->toArray();
        }

        return $userGroups;
    }

    /**
     * 获取传递的菜单规则的子规则
     * @param array $rules 菜单规则
     * @return array
     */
    private function getChildren(array $rules): array
    {
        foreach ($rules as $key => $rule) {
            if (array_key_exists($rule['id'], $this->children)) {
                $rules[$key]['children'] = $this->getChildren($this->children[$rule['id']]);
            }
        }
        return $rules;
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




    /**
     * 检查是否有某权限F
     * @param string $name     菜单规则的 name，可以传递两个，以','号隔开
     * @param int    $uid      用户ID
     * @param string $relation 如果出现两个 name,是两个都通过(and)还是一个通过即可(or)
     * @param string $mode     如果不使用 url 则菜单规则name匹配到即通过
     * @return bool
     * @throws Throwable
     */
    public function check(string $name, ?int $uid = null, string $relation = 'or', string $mode = 'url'): bool
    {
        $uid = $uid ?? $this->id;
        // 获取用户需要验证的所有有效规则列表
        $ruleList = $this->getRuleList($uid);
        if (in_array('*', $ruleList)) {
            return true;
        }

        if ($name) {
            $name = strtolower($name);
            if (str_contains($name, ',')) {
                $name = explode(',', $name);
            } else {
                $name = [$name];
            }
        }
        $list = []; //保存验证通过的规则名
        if ('url' == $mode) {
            $REQUEST = json_decode(strtolower(json_encode(request()->all(), JSON_UNESCAPED_UNICODE)), true);
        }
        foreach ($ruleList as $rule) {
            $query = preg_replace('/^.+\?/U', '', $rule);
            if ('url' == $mode && $query != $rule) {
                parse_str($query, $param); //解析规则中的param
                $intersect = array_intersect_assoc($REQUEST, $param);
                $rule      = preg_replace('/\?.*$/U', '', $rule);
                if (in_array($rule, $name) && $intersect == $param) {
                    // 如果节点相符且url参数满足
                    $list[] = $rule;
                }
            } elseif (in_array($rule, $name)) {
                $list[] = $rule;
            }
        }
        if ('or' == $relation && !empty($list)) {
            return true;
        }
        $diff = array_diff($name, $list);
        if ('and' == $relation && empty($diff)) {
            return true;
        }

        return false;
    }

    /**
     * 获得权限规则列表
     * @param int|null $uid 用户id
     * @return array
     * @throws Throwable
     */
    public function getRuleList(?int $uid = null): array
    {
        $uid = $uid ?? $this->id;
        // 读取用户规则节点
        $ids = $this->getRuleIds($uid);
        if (empty($ids)) return [];

        $originAuthRules = $this->getOriginAuthRules($uid);

        // 用户规则
        $rules = [];
        if (in_array('*', $ids)) {
            $rules[] = "*";
        }
        foreach ($originAuthRules as $rule) {
            $rules[$rule['id']] = strtolower($rule['name']);
        }
        return array_unique($rules);
    }

}