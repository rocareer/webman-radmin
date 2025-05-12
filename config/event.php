<?php
/**
 * File:        event.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/12 05:37
 * Description:
 *
 * Copyright [2014-2026] [https://rocareer.com]
 * Licensed under the Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */
use support\member\State;


return [
    'state.checkStatus' => [
        [State::class, 'checkStatus'],
    ],
    'state.updateLogin.*' => [
        [State::class, 'updateLoginState'],
    ],
    // 'member.logout' => [
    //     [\plugin\radmin\service\member\state\StateManagerInterface::class, 'logout'],
    // ]
];
