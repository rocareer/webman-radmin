<?php


namespace plugin\radmin\support\member;

use Exception;
use plugin\radmin\support\StatusCode;
use Radmin\Container;
use Radmin\Event;
use Radmin\exception\BusinessException;
use Radmin\Http;
use Radmin\orm\Rdb;
use Radmin\token\Token;
use Throwable;

abstract class Service implements InterfaceService
{
    //common
    /**
     * 登录器
     * @var InterfaceAuthenticator|mixed|null 
     */
    private ?InterfaceAuthenticator $authenticator;
    private mixed $state;

    /**
     * 角色
     * @var string 
     */
    protected string $role     = 'admin';

    /**
     * ID
     * @var int|null 
     */
    public ?int      $id       = null;
    /**
     * 用户名
     * @var string|null 
     */
    public ?string   $username = null;

    /**
     * 
     * @var mixed|null 
     */
    protected mixed $children = null;
    //instance
    protected ?object $memberModel = null;
    private mixed     $context;


    public function __construct()
    {
        $this->context       = Container::get('member.context');
        $this->authenticator = Container::get('member.authenticator');
        $this->memberModel   = Container::get('member.model');
        $this->state         = Container::get('member.state');
    }


    /**
     * @throws BusinessException
     */
    public function initialization(): ?object
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

            $this->extendMemberInfo();

            //用户状态检查
            $this->stateCheckStatus();
            //缓存用户信息
            //更新登录状态
            $this->stateUpdateLogin('success');

            return $this->memberModel;

        } catch (Exception $e) {
            //状态更新
            $this->stateUpdateLogin('failure');
            throw new BusinessException($e->getMessage(), $e->getCode(), false, [], $e);
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
        try {
            $credentials['keep'] = $keep;
            $this->memberModel   = $this->authenticator->authenticate($credentials);//设置用户信息
            $this->setMember($this->memberModel);
            Event::emit("log.authentication.{$this->role}.login.success", $this->memberModel);
            return $this->memberModel->toArray();
        } catch (Throwable $e) {
            Event::emit("log.authentication.{$this->role}.login.failure", $this->memberModel);
            throw $e;
        }
    }

    /**
     *
     * By albert  2025/05/08 04:28:10
     * @return
     */
    public function logout(): void
    {
        try {
            if (Http::request()->token) {
                Token::destroy(Http::request()->token);
            }
            Event::emit("log.authentication.{$this->role}.logout.success", $this->memberModel);
            $this->resetMember();
        } catch (Exception $e) {
            Event::emit("log.authentication.{$this->role}.logout.failure", $this->memberModel);
        }
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

    public function checkPassword($password): bool
    {
        return verify_password($password, $this->memberModel->password, ['salt' => $this->memberModel->salt]);
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
            $token = $token ?? Http::request()->token ?? getTokenFromRequest() ?? false;
            if (empty($token)) {
                throw new BusinessException('没有凭证', StatusCode::TOKEN_NOT_FOUND);
            }
            $tokenData = Token::verify($token);
            if (empty($tokenData)) {
                throw new BusinessException('凭证无效', StatusCode::TOKEN_INVALID);
            }
            $member = $this->memberModel->findById($tokenData->sub);
            if (empty($member)) {
                throw new BusinessException('用户不存在', StatusCode::MEMBER_NOT_FOUND);
            }
            $this->setMember($member);
            $this->memberModel = $member;
        } catch (Throwable $e) {
            throw new BusinessException($e->getMessage(), $e->getCode());
        }
    }

    public function extendMemberInfo(): void
    {
        $this->memberModel->roles = [$this->role];
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
     * 检查当前用户是否拥有指定角色
     * By albert  2025/04/30 04:02:04
     *
     * @param      $role
     * @param null $roles
     * @return bool
     */
    public function hasRole($role, $roles = null): bool
    {
        $payloadRoles = $roles ?? Http::request()->member->roles ?? [];
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
        $rules = Rdb::name($this->config['auth_rule'])
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
            $userGroups = Rdb::name($dbName)
                ->alias('aga')
                ->join($this->config['auth_group'] . ' ag', 'aga.group_id = ag.id', 'LEFT')
                ->field('aga.uid,aga.group_id,ag.id,ag.pid,ag.name,ag.rules')
                ->where("aga.uid='$id' and ag.status='1'")
                ->select()
                ->toArray();
        } else {
            $userGroups = Rdb::name($dbName)
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
        $this->context->clear();

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
            $REQUEST = json_decode(strtolower(json_encode(Http::request()->all(), JSON_UNESCAPED_UNICODE)), true);
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

    /**
     * 终端鉴权
     * @param string $token
     * @return   bool
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/13 00:39
     */


}