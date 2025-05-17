<?php

namespace plugin\radmin\app\admin\model\log\authentication;

use plugin\radmin\app\common\model\BaseModel;
use plugin\radmin\app\process\Http;

/**
 * Admin
 */
class Admin extends BaseModel
{
    // 表名
    protected $name = 'log_authentication_admin';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;
    protected $updateTime         = false;

    protected $type = [
        'create_time' => 'integer',
    ];


    public function record($data, $event): void
    {
        try {
            $this->startTrans();
            $saveData['name'] = $data->username??Http::request()->input('username')??"未知";
            $saveData['ip']       = $data->last_login_ip??HTTP::request()->getRealIp()??'';
            // 行为 1登录 2退出
            if (strpos($event, 'login')) {
                $saveData['action'] = 1;
            }
            if (strpos($event, 'logout')) {
                $saveData['action'] = 2;
            }

            // 状态 1成功 0失败
            if (strpos($event, 'success')) {
                $saveData['status'] = 1;
            }
            if (strpos($event, 'failure')) {
                $saveData['status'] = 0;
            }

            $this->create($saveData);
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e->getMessage());
        }
    }

}