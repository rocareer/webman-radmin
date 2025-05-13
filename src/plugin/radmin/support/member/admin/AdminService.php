<?php


/**
 * File      AdminService.php
 * Author    albert@rocareer.com
 * Time      2025-05-06 06:04:48
 * Describe  AdminService.php
 */

namespace support\member\admin;

use app\admin\model\AdminGroup;
use plugin\radmin\support\member\Service;
use plugin\radmin\support\token\Token;
use plugin\radmin\support\think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
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
    /**
     * @var array|mixed
     */
    protected mixed $children;


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

    /**
     * 附加用户信息
     * @return void
     * @throws Throwable
     */

    public function extendMemberInfo(): void
    {
        if ($this->isSuperAdmin($this->memberModel->id)) {
            $this->memberModel->roles = ['super', 'admin'];
            $this->memberModel->super = true;

        }
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
     * @param string $token
     * @return bool
     */
    public function terminal(string $token): bool
    {
        $payload=Token::verify($token);
        var_dump($payload);
        if ($payload&&$this->hasRole('super',$payload->roles)){
            return true;
        }
        return false;
    }

}