<?php

namespace Rbac\Controller;

use Doctrine\ORM\EntityManager;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Rbac\Entity\Permission;
use Rbac\Entity\Role;
use Rbac\Entity\User;
use Rbac\Manager\UserManager;
use Rbac\Service\PermissionService;
use Rbac\Service\RoleService;
use Rbac\Service\UserService;

class DefaultController extends AbstractActionController
{

    /**
     * @var array
     */
    protected $config;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var PermissionService
     */
    protected $permissionService;

    /**
     * @var RoleService
     */
    protected $roleService;

    /**
     * @param array $config
     */
    public function __construct(
        array             $config,
        EntityManager     $entityManager,
        UserService       $userService,
        PermissionService $permissionService,
        RoleService       $roleService
    )
    {
        $this->config = $config;
        $this->entityManager = $entityManager;
        $this->userService = $userService;
        $this->permissionService = $permissionService;
        $this->roleService = $roleService;
    }

    public function indexAction(): ViewModel
    {
        $inserted = [];
        $existsPrivileges = $this->entityManager->getRepository(Permission::class)->findOneBy([]);
        if (!$existsPrivileges) {
            //permissions not initialized
            //@todo test if permissions keys are provided
            $this->permissionService->createPrivileges($this->config['initialize']['permissions']);
            $inserted[] = 'privileges';
        }

        $existsRoles = $this->entityManager->getRepository(Role::class)->findOneBy([]);
        if (!$existsRoles) {
            //roles not initialized
            //@todo test if roles keys are provided
            $this->roleService->createRoles($this->config['initialize']['roles']);
            $inserted[] = 'roles';
        }

        $existsUser = $this->entityManager->getRepository(User::class)->findOneBy([]);
        if (!$existsUser) {
            $this->userService->addUsers($this->config['initialize']['users']);
            $inserted[] = 'users';
        }

        return new ViewModel([
            'inserted'=>$inserted,
        ]);

    }

}