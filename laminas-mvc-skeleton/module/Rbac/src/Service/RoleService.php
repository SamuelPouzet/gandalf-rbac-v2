<?php

namespace Rbac\Service;

use Doctrine\ORM\EntityManager;
use Laminas\Cache\Storage\StorageInterface;
use Rbac\Element\Rbac;
use Rbac\Entity\Role;
use Rbac\Entity\User;

/**
 *
 */
class RoleService
{
    /**
     * @var Rbac|null
     */
    protected $rbac = null;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var StorageInterface
     */
    protected $cache;

    /**
     * @param Rbac|null $rbac
     * @param StorageInterface $cache
     */
    public function __construct(EntityManager $entityManager, StorageInterface $cache)
    {
        $this->entityManager = $entityManager;
        $this->cache = $cache;
    }


    /**
     * @param bool $reset default false
     * @throws \Laminas\Cache\Exception\ExceptionInterface
     */
    protected function init($reset = false)
    {
        if ($reset) {
            $this->cache->removeItem('rbac_container');
        }

        $result = false;
        $this->rbac = $this->cache->getItem('rbac_container', $result);

        if (!$result) {
            $this->rbac = new Rbac();

            $roles = $this->entityManager->getRepository(Role::class)->findBy([
                'is_active' => Role::ROLE_ACTIVE,
            ]);

            foreach ($roles as $role) {
                $parents = $this->addParentsRecursive($role);
                $this->rbac->addRole($role, $parents);
            }
            $this->cache->setItem('rbac_container', $this->rbac);
        }

    }

    /**
     * @param Role $role
     * @param array $return
     * @return array|mixed
     */
    protected function addParentsRecursive(Role $role, $return = [])
    {
        $parents = $role->getParents();
        foreach ($parents as $parent) {
            if (!in_array($parent, $return)) {
                //avoid loop
                $return[] = $parent;
                $return = $this->addParentsRecursive($parent, $return);
            }

        }
        return $return;
    }

    /**
     * @param User $user
     * @param string $role
     * @return bool
     * @throws \Laminas\Cache\Exception\ExceptionInterface
     */
    public function userHasRole(User $user, string $role): bool
    {
        if (!$this->rbac) {
            $this->init();
        }

        return $this->rbac->hasRole($role, $user);
    }

    /**
     * @param User $user
     * @param string $permission
     * @return bool
     * @throws \Laminas\Cache\Exception\ExceptionInterface
     */
    public function userHasPermission(User $user, string $permission): bool
    {
        if (!$this->rbac) {
            $this->init();
        }

        return $this->rbac->hasPermission($permission, $user);
    }

}