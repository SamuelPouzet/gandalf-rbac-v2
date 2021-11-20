<?php

namespace Rbac\Manager;

use Doctrine\ORM\EntityManager;
use Rbac\Entity\Permission;

/**
 *
 */
class PermissionManager
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
     * @return Permission
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function add(array $data): Permission
    {
        $entity = new Permission();
        $entity->setName($data['name']);
        $entity->setDescription($data['description']);
        $entity->setIsActive($data['is_active']);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        return $entity;
    }


}