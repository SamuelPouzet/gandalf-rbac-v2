<?php

namespace Rbac\Service;

use Doctrine\ORM\EntityManager;
use Rbac\Entity\Role;
use Rbac\Entity\User;
use Rbac\Manager\UserManager;

class UserService
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager, EntityManager $entityManager)
    {
        $this->userManager = $userManager;
        $this->entityManager = $entityManager;
    }

    public function addUsers(array $users): void
    {
        foreach ($users as $login => $config) {
            $data = [];
            $data['login'] = $login;
            $data['name'] = $config['name'] ?? '';
            $data['firstname'] = $config['firstName'] ?? '';
            $data['email'] = $config['email'] ?? uniqid() . '@exemple.com';
            $data['avatar']['name'] = $config['avatar'] ?? 'default.png';
            $data['password'] = $config['password'] ?? 'topS1c3t';
            $data['status'] = User::USER_ACTVATED;
            if (isset($config['roles'])) {
                $data['roles'] = $this->entityManager->getRepository(Role::class)->getByRoleNames($config['roles']);
            }

            $this->userManager->add($data);
        }

    }


}