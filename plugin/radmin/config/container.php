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

use DI\ContainerBuilder;
use plugin\radmin\support\member\admin\AdminAuthenticator;
use plugin\radmin\support\member\admin\AdminModel;
use plugin\radmin\support\member\admin\AdminService;
use plugin\radmin\support\member\admin\AdminState;
use plugin\radmin\support\member\InterfaceAuthenticator;
use plugin\radmin\support\member\InterfaceModel;
use plugin\radmin\support\member\InterfaceService;
use plugin\radmin\support\member\InterfaceState;
use plugin\radmin\support\member\user\UserAuthenticator;
use plugin\radmin\support\member\user\UserModel;
use plugin\radmin\support\member\user\UserService;
use plugin\radmin\support\member\user\UserState;
use Psr\Container\ContainerInterface;
use Radmin\Context;
use Radmin\Http;

use function DI\create;
use function DI\factory;

$definitions= [

    // request

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
    InterfaceService::class => factory(function ($container) {
        $context = $container->get('member.context');
        $role= Http::request()->role??$context->get('role');
        $serviceMap = $container->get('member.service.map');
        return $container->get($serviceMap[$role]);
    }),
    // 动态绑定状态
    InterfaceState::class => factory(function ($container) {
        $context = $container->get('member.context');
        $role= Http::request()->role??$context->get('role');
        $stateMap = $container->get('member.state.map');
        return $container->get($stateMap[$role]);
    }),
    // 动态绑定模型
    InterfaceModel::class => factory(function ($container) {
        $context = $container->get('member.context');
        $role= Http::request()->role??$context->get('role');
        $modelMap = $container->get('member.model.map');
        return $container->get($modelMap[$role]);
    }),
    // 动态绑定认证器
    InterfaceAuthenticator::class => factory(function ($container) {
        $context = $container->get('member.context');
        $role= Http::request()->role??$context->get('role');
        $authenticatorMap = $container->get('member.authenticator.map');
        return $container->get($authenticatorMap[$role]);
    }),

];


$builder = new ContainerBuilder();
$builder->addDefinitions($definitions);
$builder->useAutowiring(true);
$builder->enableCompilation(runtime_path() . '/radmin/definitions');
return $builder->build();