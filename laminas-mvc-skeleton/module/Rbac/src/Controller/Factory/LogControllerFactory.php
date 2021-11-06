<?php

namespace Rbac\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Rbac\Controller\LogController;
use Rbac\Service\AuthService;

class LogControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): LogController
    {
        $authService = $container->get(AuthService::class);
        return new LogController($authService);
    }
}