<?php

namespace Rbac\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Rbac\Adapter\UserAdapter;
use Rbac\Service\AccountService;
use Rbac\Service\AuthService;

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
        $config = $container->get('config');
        if (isset($config['access_filter'])) {
            $config = $config['access_filter'];
        } else {
            //if config not found, no access granted
            $config = [
                'mode' => 'restrictive',
                'parameters' => [],
            ];
        }

        $accountService = $container->get(AccountService::class);
        $adapter = $container->get(UserAdapter::class);

        return new AuthService($config, $accountService, $adapter);
    }

}