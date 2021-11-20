<?php

namespace Rbac\Service;

use Doctrine\ORM\EntityManager;
use Rbac\Entity\Permission;
use Rbac\Manager\PermissionManager;

class PermissionService
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var PermissionManager
     */
    protected $permissionManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager, PermissionManager $permissionManager)
    {
        $this->entityManager = $entityManager;
        $this->permissionManager = $permissionManager;
    }

    public function createPrivileges(array $privileges)
    {
        foreach ($privileges as $name=>$config){
            $data = [];
            $data['name'] = $name;
            $data['description'] = $config['description']??'';
            $data['is_active'] = true;
            $this->permissionManager->add($data);
        }
    }


}