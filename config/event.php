<?php

use support\member\State;

return [
    'state.checkStatus' => [
        [State::class, 'checkStatus'],
        // ...其它事件处理函数...
    ],
    'state.updateLogin.*' => [
        [State::class, 'updateLoginState'],
        // ...其它事件处理函数...
    ],
    // 'member.logout' => [
    //     [\plugin\radmin\service\member\state\StateManagerInterface::class, 'logout'],
    //     // ...其它事件处理函数...
    // ]
];
