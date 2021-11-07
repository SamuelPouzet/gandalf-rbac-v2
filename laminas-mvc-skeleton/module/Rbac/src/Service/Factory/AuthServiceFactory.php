<?php

namespace Rbac\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\Authentication\Storage\Session;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Session\SessionManager;
use Rbac\Adapter\UserAdapter;
use Rbac\Service\AccountService;
use Rbac\Service\AuthService;
use Rbac\Service\RoleService;
use Rbac\Service\SessionService;

/**
 * AuthServiceFactory
 */
class AuthServiceFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AuthService
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): AuthService
    {
        $globalconfig = $container->get('config');
        if (isset($globalconfig['access_filter'])) {
            $config = $globalconfig['access_filter'];
        } else {
            //if config not found, no access granted
            $config = [
                'mode' => 'restrictive',
                'parameters' => [],
            ];
        }

        $accountService = $container->get(AccountService::class);
        $roleService = $container->get(RoleService::class);
        $adapter = $container->get(UserAdapter::class);
        $sessionService = $container->get(SessionService::class);

        return new AuthService($config, $accountService, $roleService, $adapter, $sessionService);
    }

}