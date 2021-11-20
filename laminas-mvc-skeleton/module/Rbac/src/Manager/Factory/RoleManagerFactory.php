<?php

namespace Rbac\Manager\Factory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Rbac\Manager\RoleManager;
use Rbac\Manager\UserManager;

class RoleManagerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return RoleManager
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): RoleManager
    {
        $entityManager = $container->get(EntityManager::class);
        return new RoleManager($entityManager);
    }
}