<?php

namespace Rbac\Manager;

use Doctrine\ORM\EntityManager;
use Rbac\Entity\Role;

/**
 *
 */
class RoleManager
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param array $data
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function add(array $data): Role
    {
        $role = new Role();
        $role->setIsActive(Role::ROLE_ACTIVE);
        $role->setName($data['name']);
        $role->setDescription($data['description']);
        foreach ($data['permissions'] as $permission){
            $role->addPermission($permission);
        }

        $this->entityManager->persist($role);
        $this->entityManager->flush();

        return $role;
    }

    public function updateParents(Role $role, array $parents): void
    {
        foreach ($parents as $parent){
            $role->addParent($parent);
            $parent->addChild($role);
            $this->entityManager->persist($role);
            $this->entityManager->persist($parent);
        }
        $this->entityManager->flush();
    }


}