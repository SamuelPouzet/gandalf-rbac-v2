<?php

namespace Rbac;

use Application\Controller\IndexController;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Laminas\Router\Http\Literal;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Rbac\Adapter\Factory\UserAdapterFactory;
use Rbac\Adapter\UserAdapter;
use Rbac\Controller\Factory\LogControllerFactory;
use Rbac\Controller\LogController;
use Rbac\Service\AccountService;
use Rbac\Service\AuthService;
use Rbac\Service\Factory\AccountServiceFactory;
use Rbac\Service\Factory\AuthServiceFactory;

return [
    'router' => [
        'routes' => [
            'login' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/log',
                    'defaults' => [
                        'controller' => LogController::class,
                        'action' => 'login',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            LogController::class => LogControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            AuthService::class => AuthServiceFactory::class,
            AccountService::class => AccountServiceFactory::class,

            UserAdapter::class => UserAdapterFactory::class,
        ],
    ],
    'access_filter' => [
        'mode' => 'restrictive',
        'parameters' => [
            IndexController::class => [
                'index' => '@',
            ]
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