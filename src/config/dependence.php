<?php
/**
 * This file is part of webman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */


use plugin\radmin\exception\BusinessException;

use plugin\radmin\support\member\admin\AdminAuthenticator;
use plugin\radmin\support\member\admin\AdminService;
use plugin\radmin\support\member\admin\AdminState;
use plugin\radmin\support\member\Context;
use plugin\radmin\support\member\InterfaceAuthenticator;
use plugin\radmin\support\member\InterfaceModel;
use plugin\radmin\support\member\InterfaceService;
use plugin\radmin\support\member\InterfaceState;
use plugin\radmin\support\member\admin\AdminModel;
use plugin\radmin\support\member\user\UserAuthenticator;
use plugin\radmin\support\member\user\UserModel;
use plugin\radmin\support\member\user\UserService;
use plugin\radmin\support\member\user\UserState;

use plugin\radmin\support\Request;
use Psr\Container\ContainerInterface;



use function DI\autowire;
use function DI\create;
use function DI\get;

return [

    // member
    'member.roles'=> [
        'admin','user'
    ],
    'member.context'       => create(Context::class),

    // member map
    'member.service.map'   => [
        'admin' => AdminService::class,
        'user'  => UserService::class,
    ],
    'member.state.map'   => [
        'admin' => AdminState::class,
        'user'  => UserState::class,
    ],
    'member.model.map'   => [
        'admin' => AdminModel::class,
        'user'  => UserModel::class,
    ],
    'member.authenticator.map'   => [
        'admin' => AdminAuthenticator::class,
        'user'  => UserAuthenticator::class,
    ],

    // 别名
    'member.service'       => function (ContainerInterface $container) {
        return $container->get(InterfaceService::class);
    },
    'member.model'         => function (ContainerInterface $container) {
        return $container->get(InterfaceModel::class);
    },
    'member.state'         => function (ContainerInterface $container) {
        return $container->get(InterfaceState::class);
    },
    'member.authenticator' => function (ContainerInterface $container) {
        return $container->get(InterfaceAuthenticator::class);
    },

    // 动态绑定服务
    InterfaceService::class => DI\factory(function ($container,?Request $request=null) {
        $context = $container->get('member.context');
        $role= $request->role??$context['role'];
        $serviceMap = $container->get('member.service.map');
        return $container->get($serviceMap[$role]);
    }),
    // 动态绑定状态
    InterfaceState::class => DI\factory(function ($container,?Request $request=null) {
        $context = $container->get('member.context');
        $role= $request->role??$context['role'];
        $stateMap = $container->get('member.state.map');
        return $container->get($stateMap[$role]);
    }),
    // 动态绑定模型
    InterfaceModel::class => DI\factory(function ($container,?Request $request=null) {
        $context = $container->get('member.context');
        $role= $request->role??$context['role'];
        $modelMap = $container->get('member.model.map');
        return $container->get($modelMap[$role]);
    }),
    // 动态绑定认证器
    InterfaceAuthenticator::class => DI\factory(function ($container,?Request $request=null) {
        $context = $container->get('member.context');
        $role= $request->role??$context['role'];
        $authenticatorMap = $container->get('member.authenticator.map');
        return $container->get($authenticatorMap[$role]);
    }),

];