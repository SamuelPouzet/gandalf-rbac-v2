<?php

namespace Rbac\Service\Factory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Rbac\Manager\TokenManager;
use Rbac\Service\AccountService;
use Rbac\Service\MailerService;
use Rbac\Service\SessionService;

class AccountServiceFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): AccountService
    {
        $entityManager = $container->get(EntityManager::class);
        $sessionManager = $container->get(SessionService::class);
        $tokenManager = $container->get(TokenManager::class);
        $mailerService = $container->get(MailerService::class);

        return new AccountService($entityManager, $sessionManager, $tokenManager, $mailerService);

    }

}