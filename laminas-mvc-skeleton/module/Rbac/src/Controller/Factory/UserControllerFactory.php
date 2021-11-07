<?php

namespace Rbac\Controller\Factory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Rbac\Controller\UserController;
use Rbac\Manager\UserManager;

class UserControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): UserController
    {
        $entityManager = $container->get(EntityManager::class);
        $userManager = $container->get(UserManager::class);
        return new UserController($entityManager, $userManager);
    }

}