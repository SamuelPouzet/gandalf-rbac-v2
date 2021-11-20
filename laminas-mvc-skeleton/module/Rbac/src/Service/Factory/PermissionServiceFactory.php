<?php

namespace Rbac\Service\Factory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Rbac\Manager\PermissionManager;
use Rbac\Service\PermissionService;

class PermissionServiceFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $entityManager = $container->get(EntityManager::class);
        $permissionManager = $container->get(PermissionManager::class);
        return new PermissionService($entityManager, $permissionManager);
    }

}