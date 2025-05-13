<?php
/**
 * File:        BackupEvent.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/12 19:14
 * Description:
 *
 * Copyright [2014-2026] [https://rocareer.com]
 * Licensed under the Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */

namespace support\backup;

use app\admin\model\security\backup\Log;
use stdClass;

class BackupEvent
{
    protected Log $model;
    public function initialize():void
    {
        $this->model = new Log();
    }


    public function log($back,$event)
    {
        if ($event=='backup.log.add.success'){

        }
        if ($event=='backup.log.add.faile'){
        }


    }
}