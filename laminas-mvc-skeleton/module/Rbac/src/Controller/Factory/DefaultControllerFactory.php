<?php

namespace Rbac\Controller\Factory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Rbac\Controller\DefaultController;
use Rbac\Service\PermissionService;
use Rbac\Service\RoleService;
use Rbac\Service\UserService;

class DefaultControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $config = $container->get('config')['user'] ?? [];

        //set up default config user: admin, password: t0ps3cret , mail: admin@exemple.com
        $configDefault = [
            'initialize' => [
                'administrator' => [
                    'login' => 'root',
                    'password' => 'secur1ty',
                    'avatar' => 'default.png',
                    'name' => 'administrator',
                    'firstname' => 'admin',
                    'email' => 'support@monsite.fr',
                    'status' => \Rbac\Entity\User::USER_ACTVATED,
                ],
            ],
        ];

        $config = array_merge_recursive($config, $configDefault);

        $entityManager = $container->get(EntityManager::class);
        $permissionService = $container->get(PermissionService::class);
        $roleService = $container->get(RoleService::class);
        $userService = $container->get(UserService::class);

        return new DefaultController($config, $entityManager, $userService, $permissionService, $roleService);
    }

}