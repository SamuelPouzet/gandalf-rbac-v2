<?php

namespace Rbac\Controller\Plugin\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Rbac\Controller\Plugin\CurrentUserPlugin;
use Rbac\Service\AccountService;

/**
 * CurrentUserPluginFactory
 */
class CurrentUserPluginFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return CurrentUserPlugin
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): CurrentUserPlugin
    {
        $accountservice = $container->get(AccountService::class);
        return new CurrentUserPlugin($accountservice);
    }

}