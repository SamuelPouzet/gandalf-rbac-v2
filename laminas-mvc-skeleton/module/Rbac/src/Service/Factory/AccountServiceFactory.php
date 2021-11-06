<?php

namespace Rbac\Service\Factory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Rbac\Service\AccountService;

class AccountServiceFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): AccountService
    {
        $entityManager = $container->get(EntityManager::class);
        return new AccountService($entityManager);

    }

}