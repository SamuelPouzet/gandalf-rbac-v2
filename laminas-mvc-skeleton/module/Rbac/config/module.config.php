<?php

namespace Rbac;

use Application\Controller\IndexController;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Rbac\Adapter\Factory\UserAdapterFactory;
use Rbac\Adapter\UserAdapter;
use Rbac\Controller\DefaultController;
use Rbac\Controller\Factory\DefaultControllerFactory;
use Rbac\Controller\Factory\LogControllerFactory;
use Rbac\Controller\Factory\UserControllerFactory;
use Rbac\Controller\LogController;
use Rbac\Controller\Plugin\CurrentUserPlugin;
use Rbac\Controller\Plugin\Factory\CurrentUserPluginFactory;
use Rbac\Controller\UserController;
use Rbac\Manager\Factory\PermissionManagerFactory;
use Rbac\Manager\Factory\RoleManagerFactory;
use Rbac\Manager\Factory\TokenManagerFactory;
use Rbac\Manager\Factory\UserManagerFactory;
use Rbac\Manager\PermissionManager;
use Rbac\Manager\RoleManager;
use Rbac\Manager\TokenManager;
use Rbac\Manager\UserManager;
use Rbac\Service\AccountService;
use Rbac\Service\AuthService;
use Rbac\Service\Factory\AccountServiceFactory;
use Rbac\Service\Factory\AuthServiceFactory;
use Rbac\Service\Factory\MailerServiceFactory;
use Rbac\Service\Factory\PermissionServiceFactory;
use Rbac\Service\Factory\RoleServiceFactory;
use Rbac\Service\Factory\SessionServiceFactory;
use Rbac\Service\Factory\UserServiceFactory;
use Rbac\Service\MailerService;
use Rbac\Service\PermissionService;
use Rbac\Service\RoleService;
use Rbac\Service\SessionService;
use Rbac\Service\UserService;

return [
    'router' => [
        'routes' => [
            'login' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/login',
                    'defaults' => [
                        'controller' => LogController::class,
                        'action' => 'login',
                    ],
                ],
            ],
            'logout' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/logout',
                    'defaults' => [
                        'controller' => LogController::class,
                        'action' => 'logout',
                    ],
                ],
            ],
            'newaccount' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/signin',
                    'defaults' => [
                        'controller' => LogController::class,
                        'action' => 'signin',
                    ],
                ],
            ],
            'activate' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/activate/:token',
                    'defaults' => [
                        'controller' => LogController::class,
                        'action' => 'activate',
                    ],
                ],
            ],
            'user' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/user[/:action[/:id]]',
                    'defaults' => [
                        'controller' => UserController::class,
                        'action' => 'list',
                    ],
                ],
            ],

        ],
    ],
    'controllers' => [
        'factories' => [
            LogController::class => LogControllerFactory::class,
            UserController::class => UserControllerFactory::class,
            DefaultController::class => DefaultControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            AuthService::class => AuthServiceFactory::class,
            AccountService::class => AccountServiceFactory::class,
            RoleService::class => RoleServiceFactory::class,
            SessionService::class => SessionServiceFactory::class,
            MailerService::class => MailerServiceFactory::class,
            PermissionService::class => PermissionServiceFactory::class,
            UserService::class => UserServiceFactory::class,

            UserAdapter::class => UserAdapterFactory::class,

            UserManager::class => UserManagerFactory::class,
            TokenManager::class => TokenManagerFactory::class,
            PermissionManager::class => PermissionManagerFactory::class,
            RoleManager::class => RoleManagerFactory::class,
        ],
    ],
    'access_filter' => [
        'mode' => 'restrictive',
        'parameters' => [
            IndexController::class => [
                // 'index' => ['+moderate', '#role.user2', '@gandalf2'],
                'index' => '@',
            ],
            UserController::class => [
                // 'index' => ['+moderate', '#role.user2', '@gandalf2'],
                'list' => '#role.admin2',
                'show' => '#role.admin',
                'update' => '#role.admin',
                'add' => '#role.admin',
                'password' => '#role.admin',
            ],
            DefaultController::class => [
                'index' => '*',
            ],
        ]
    ],
    'controller_plugins' => [
        'factories' => [
            CurrentUserPlugin::class => CurrentUserPluginFactory::class,
        ],
        'aliases' => [
            'currentUser' => CurrentUserPlugin::class,
        ]
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],
    'view_manager' => [
        'doctype' => 'HTML5',
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

];