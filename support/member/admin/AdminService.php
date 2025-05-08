<?php


/**
 * File      AdminService.php
 * Author    albert@rocareer.com
 * Time      2025-05-06 06:04:48
 * Describe  AdminService.php
 */

namespace support\member\admin;

use app\admin\model\AdminGroup;
use support\member\Service;
use support\token\Token;
use support\think\Db;
use Throwable;

class AdminService extends Service
{
    protected string $role = 'admin';
    public ?int      $id   = null;
    /**
     * 默认配置
     * @var array|string[]
     */
    protected array $config = [
        'auth_group'        => 'admin_group', // 用户组数据表名
        'auth_group_access' => 'admin_group_access', // 用户-用户组关系表
        'auth_rule'         => 'admin_rule', // 权限规则表
    ];


    public function initialize(): void
    {
        if (request()->member) {
            $this->id = request()->member->id;
        }
    }

    /**
     * 是否是超级管理员
     * @throws Throwable
     */
    public function isSuperAdmin(?int $id = null): bool
    {
        return in_array('*', $this->getRuleIds($id));
    }


    public function extendMemberInfo(): void
    {
        if ($this->isSuperAdmin($this->memberModel->id)) {
            $this->memberModel->roles = ['super', 'admin'];
            $this->memberModel->super = true;

        }
    }

    /**
     * 获取菜单规则列表
     * @access public
     * @param int $uid 用户ID
     * @return array
     * @throws Throwable
     */
    public function getMenus(int $uid): array
    {
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
     * @param int $uid 用户id
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
     * 获得权限规则原始数据
     * @param int $uid 用户id
     * @return array
     * @throws Throwable
     */
    public function getOriginAuthRules(int $uid): array
    {
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
     * 获取管理员所在分组的所有子级分组
     * @return array
     * @throws Throwable
     */
    public function getAdminChildGroups(): array
    {
        $groupIds = Db::name('admin_group_access')
            ->where('uid', $this->id)
            ->select();
        $children = [];
        foreach ($groupIds as $group) {
            $this->getGroupChildGroups($group['group_id'], $children);
        }
        return array_unique($children);
    }

    /**
     * 获取分组内的管理员
     * @param array $groups
     * @return array 管理员数组
     */
    public function getGroupAdmins(array $groups): array
    {
        return Db::name('admin_group_access')
            ->where('group_id', 'in', $groups)
            ->column('uid');
    }

    /**
     * 获取一个分组下的子分组
     * @param int   $groupId  分组ID
     * @param array $children 存放子分组的变量
     * @return void
     * @throws Throwable
     */
    public function getGroupChildGroups(int $groupId, array &$children): void
    {
        $childrenTemp = AdminGroup::where('pid', $groupId)
            ->where('status', 1)
            ->select();
        foreach ($childrenTemp as $item) {
            $children[] = $item['id'];
            $this->getGroupChildGroups($item['id'], $children);
        }
    }

    /**
     * 获取拥有 `所有权限` 的分组
     * @param string $dataLimit       数据权限
     * @param array  $groupQueryWhere 分组查询条件（默认查询启用的分组：[['status','=',1]]）
     * @return array 分组数组
     * @throws Throwable
     */
    public function getAllAuthGroups(string $dataLimit, array $groupQueryWhere = [['status', '=', 1]]): array
    {
        // 当前管理员拥有的权限
        $rules         = $this->getRuleIds($this->id);
        $allAuthGroups = [];
        $groups        = AdminGroup::where($groupQueryWhere)->select();
        foreach ($groups as $group) {
            if ($group['rules'] == '*') {
                continue;
            }
            $groupRules = explode(',', $group['rules']);

            // 及时break, array_diff 等没有 in_array 快
            $all = true;
            foreach ($groupRules as $groupRule) {
                if (!in_array($groupRule, $rules)) {
                    $all = false;
                    break;
                }
            }
            if ($all) {
                if ($dataLimit == 'allAuth' || ($dataLimit == 'allAuthAndOthers' && array_diff($rules, $groupRules))) {
                    $allAuthGroups[] = $group['id'];
                }
            }
        }
        return $allAuthGroups;
    }

    /**
     * 终端鉴权
     * By albert  2025/05/04 00:16:27
     * @param $token
     * @return bool
     */
    public function terminal($token)
    {
        $valid = Token::verify($token);
        if ($valid) {
            if ($this->isSuperAdmin($valid->user_id))
                return true;
        }
        return false;
    }

}