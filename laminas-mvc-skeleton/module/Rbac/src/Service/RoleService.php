<?php

namespace Rbac\Service;

use Doctrine\ORM\EntityManager;
use Rbac\Element\Rbac;
use Rbac\Entity\Role;
use Rbac\Entity\User;

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
     * @param Rbac|null $rbac
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    protected function init()
    {
        $this->rbac = new Rbac();

        $roles = $this->entityManager->getRepository(Role::class)->findBy([
            'is_active'=>Role::ROLE_ACTIVE,
        ]);

        foreach ($roles as $role)
        {
            $parents = $this->addParentsRecursive($role);
            $this->rbac->addRole($role, $parents);
        }


    }

    protected function addParentsRecursive(Role $role, $return = [])
    {
        $parents = $role->getParents();
        foreach ($parents as $parent){
            if(!in_array($parent, $return)){
                //avoid loop
                $return[] = $parent;
                $return = $this->addParentsRecursive($parent, $return);
            }

        }
        return $return;
    }

    public function userHasRole(User $user, string $role): bool
    {
        if(!$this->rbac){
            $this->init();
        }

        return $this->rbac->hasRole($role, $user);

    }

}