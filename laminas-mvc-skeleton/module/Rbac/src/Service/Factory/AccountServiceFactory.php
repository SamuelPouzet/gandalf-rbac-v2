<?php

namespace Rbac\Service\Factory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Laminas\Authentication\Storage\Session;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Session\SessionManager;
use Rbac\Service\AccountService;

class AccountServiceFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): AccountService
    {
        $globalconfig = $container->get('config');
        $entityManager = $container->get(EntityManager::class);
        $sessionManager = $container->get(SessionManager::class);
        $authStorage = new Session($globalconfig['session_prefix'] ?? 'gandalfAuthRbac', 'session', $sessionManager);
        return new AccountService($entityManager, $authStorage);

    }

}