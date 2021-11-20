<?php

namespace Rbac\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Rbac\Manager\BanManager;
use Rbac\Service\FailToBanService;

class FailToBanServiceFactory implements FactoryInterface
{


    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $config = $container->get('config');
        if(!isset($config['failtoban']) || !is_array($config['failtoban']) ){
            throw new \Exception('config not found for the failtoban');
        }

        $banManager = $container->get(BanManager::class);

        return new FailToBanService($banManager, $config['failtoban']);
    }

}