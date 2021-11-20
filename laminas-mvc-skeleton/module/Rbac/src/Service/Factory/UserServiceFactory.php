<?php

namespace Rbac\Service\Factory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Rbac\Manager\UserManager;
use Rbac\Service\UserService;

class UserServiceFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): UserService
    {
        $userManager = $container->get(UserManager::class);
        $entityManager = $container->get(EntityManager::class);
        return new UserService($userManager, $entityManager);
    }
}