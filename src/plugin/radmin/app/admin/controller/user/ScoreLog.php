<?php

namespace plugin\radmin\app\admin\controller\user;

use plugin\radmin\support\Response;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use plugin\radmin\app\admin\model\User;
use plugin\radmin\app\admin\model\UserScoreLog;
use plugin\radmin\app\common\controller\Backend;

class ScoreLog extends Backend
{
    /**
     * @var object
     * @phpstan-var UserScoreLog
     */
    protected object $model;

    protected array $withJoinTable = ['user'];

    // 排除字段
    protected string|array $preExcludeFields = ['create_time'];

    protected string|array $quickSearchField = ['user.username', 'user.nickname'];

    public function initialize():void
    {
        parent::initialize();
        $this->model = new UserScoreLog();
    }

    /**
     * 添加
     * @param int $userId
     * @return Response
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function add(int $userId = 0): Response
    {
        if ($this->request->isPost()) {
            return parent::add();
        }

        $user = User::where('id', $userId)->find();
        if (!$user) {
            return $this->error(__("The user can't find it"));
        }
        return $this->success('', [
            'user' => $user
        ]);
    }
}