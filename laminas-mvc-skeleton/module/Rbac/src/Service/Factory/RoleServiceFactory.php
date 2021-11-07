<?php

namespace Rbac\Service\Factory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
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
        return new RoleService($entityManager);
    }

}