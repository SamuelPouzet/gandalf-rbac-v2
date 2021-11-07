<?php

namespace Rbac\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\Authentication\Storage\Session;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Session\SessionManager;
use Rbac\Service\SessionService;

class SessionServiceFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): SessionService
    {
        $globalconfig = $container->get('config');

        $sessionManager = $container->get(SessionManager::class);

        $authStorage = new Session($globalconfig['session_prefix']??'gandalfAuthRbac', 'session', $sessionManager);

        return new SessionService($authStorage, $sessionManager);
    }

}