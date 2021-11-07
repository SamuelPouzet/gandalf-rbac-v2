<?php

namespace Rbac\Element;

use Doctrine\Common\Collections\ArrayCollection;
use Rbac\Entity\Role;
use Rbac\Entity\User;

class Rbac
{

    /**
     * @var array
     */
    protected $roles;

    public function __construct()
    {

    }

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
        foreach ($parents as $parent) {
            $roles[] = $parent->getName();
        }

        $this->roles[$role->getName()] = $roles;
        return $this;
    }

    public function hasRole(string $role, User $user): bool
    {
        foreach ($user->getRoles() as $userRole) {
            $roleName = $userRole->getName();
            if (!isset($this->roles[$roleName])) {
                //role doesn't exist configuration error
                continue;
            }

            if (in_array($role, $this->roles[$roleName])) {
                //the user has a parent with the role
                return true;
            }
        }

        return false;
    }


}