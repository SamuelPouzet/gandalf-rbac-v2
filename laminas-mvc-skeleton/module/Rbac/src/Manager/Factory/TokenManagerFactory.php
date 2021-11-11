<?php

namespace Rbac\Manager\Factory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Rbac\Manager\TokenManager;
use Rbac\Manager\UserManager;

/**
 * UserManagerFactory
 */
class TokenManagerFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return EntityManager
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): TokenManager
    {
        $entityManager = $container->get(EntityManager::class);
        return new TokenManager($entityManager);
    }

}