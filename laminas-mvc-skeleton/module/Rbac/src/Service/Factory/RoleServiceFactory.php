<?php

namespace Rbac\Service\Factory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Rbac\Manager\RoleManager;
use Rbac\Service\RoleService;

/**
 * RoleServiceFactory
 */
class RoleServiceFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return RoleService
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): RoleService
    {
        $entityManager = $container->get(EntityManager::class);
        $cache = $container->get('FilesystemCache');
        $roleManager = $container->get(RoleManager::class);
        return new RoleService($entityManager, $cache, $roleManager);
    }

}