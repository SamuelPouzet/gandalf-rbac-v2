<?php

namespace Rbac\Service;

use Doctrine\ORM\EntityManager;
use Laminas\Cache\Storage\StorageInterface;
use Rbac\Element\Rbac;
use Rbac\Entity\Permission;
use Rbac\Entity\Role;
use Rbac\Entity\User;
use Rbac\Manager\RoleManager;

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
     * @var RoleManager
     */
    protected $roleManager;

    /**
     * @param EntityManager $entityManager
     * @param StorageInterface $cache
     * @param RoleManager $roleManager
     */
    public function __construct(EntityManager $entityManager, StorageInterface $cache, RoleManager $roleManager)
    {
        $this->entityManager = $entityManager;
        $this->cache = $cache;
        $this->roleManager = $roleManager;
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

    public function createRoles(array $roles)
    {
        $persistedRoles = [];
        foreach ($roles as $name=>$config){

            $data = [];
            $data['name'] = $name;
            $data['description'] = $config['description']??'';
            $data['is_active'] = true;

            if(!is_null($config['permissions'])){
                $data['permissions'] = $this->entityManager
                    ->getRepository(Permission::class)
                    ->getByPermissionNames($config['permissions']);
            }

            $role = $this->roleManager->add($data);
            $persistedRoles[$role->getName()] = $role;
        }
        //all roles persisted, we can create heritage
        foreach ($roles as $name=>$config){
            if(! $config['parents']){
                continue;
            }
            $role = $persistedRoles[$name];
            $parents = [];
            foreach ($config['parents'] as $parent){
                $parents[] = $persistedRoles[$parent];
            }
            $this->roleManager->updateParents($role, $parents);
        }
    }

}