<?php

namespace Rbac\Element;

use Doctrine\Common\Collections\ArrayCollection;
use Rbac\Entity\Role;
use Rbac\Entity\User;

/**
 *
 */
class Rbac
{

    /**
     * @var array
     */
    protected $roles;


    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param Role $role
     * @param array[Role] $parents
     * @return Rbac
     */
    public function addRole(Role $role, array $parents = []): Rbac
    {
        $roles = [$role->getName()];
        $permissions = $this->addPermissions($role);
        foreach ($parents as $parent) {
            $roles[] = $parent->getName();
            $permissions = array_merge($permissions, $this->addPermissions($parent) );
        }
        $this->roles[$role->getName()]['role'] = $roles;
        $this->roles[$role->getName()]['permissions'] = $permissions;
        return $this;
    }

    /**
     * @param Role $role
     * @return array
     */
    protected function addPermissions(Role $role): array
    {
        $return = [];
        $permissions = $role->getPermissions();
        foreach ($permissions as $permission){
            $return[] = $permission->getName();
        }
        return $return;
    }

    /**
     * @param string $role
     * @param User $user
     * @return bool
     */
    public function hasRole(string $role, User $user): bool
    {
        foreach ($user->getRoles() as $userRole) {
            $roleName = $userRole->getName();
            if (!isset($this->roles[$roleName]['role'])) {
                //role doesn't exist configuration error
                continue;
            }

            if (in_array($role, $this->roles[$roleName]['role'])) {
                //the user has a parent with the role
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $permission
     * @param User $user
     * @return bool
     */
    public function hasPermission(string $permission, User $user): bool
    {
        foreach ($user->getRoles() as $userRole) {
            $roleName = $userRole->getName();
            if (!isset($this->roles[$roleName]['permissions'])) {
                //role doesn't exist configuration error
                continue;
            }

            if (in_array($permission, $this->roles[$roleName]['permissions'])) {
                //the user has a parent with the role
                return true;
            }
        }

        return false;
    }


}