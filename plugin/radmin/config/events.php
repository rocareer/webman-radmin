<?php
/**
 * File:        event.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/12 05:37
 * Description:
 *
 * Copyright (c) Rocareer Team. All rights reserved.
 * Author: albert@rocareer.com
 */

use plugin\radmin\app\admin\model\data\Backup;
use plugin\radmin\app\admin\model\log\authentication\Admin;
use plugin\radmin\app\admin\model\log\data\Restore as RestoreLog;
use plugin\radmin\support\member\State;
use plugin\radmin\app\admin\model\log\data\Backup as BackupLog;

return [
    //状态事件
    'state.checkStatus'   => [
        [State::class, 'checkStatus'],
    ],
    'state.updateLogin.*' => [
        [State::class, 'updateLoginState'],
    ],


    //数据日志事件
    'log.data.backup'    => [
        [BackupLog::class, 'record'],
    ],
    'log.data.restore'   => [
        [RestoreLog::class, 'record'],
    ],
    //管理员日志事件
    'log.authentication.admin.*'   => [
        [Admin::class, 'record'],
    ],

    //数据备份事件
    'data.backup.record' => [
        [Backup::class, 'record'],
    ],

];
