<?php

namespace Rbac\Adapter\Factory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Rbac\Adapter\UserAdapter;
use Rbac\Entity\User;

class UserAdapterFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): UserAdapter
    {
        $config = $container->get('config');
        if(isset($config['auth']['authBy'])){
            $authmethod = $config['auth']['authBy'];
            if(! property_exists($authmethod, User::class)){
                //someone configurated tu search by an unknown property
                throw new \Exception('config method not found');
            }
        }else{
            $authmethod = 'email';
        }

        $entityManager = $container->get(EntityManager::class);

        return new UserAdapter($entityManager, $authmethod);
    }
}