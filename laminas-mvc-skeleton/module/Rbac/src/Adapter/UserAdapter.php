<?php

namespace Rbac\Adapter;

use Doctrine\ORM\EntityManager;
use Laminas\Authentication\Adapter\AdapterInterface;
use Rbac\Entity\User;

class UserAdapter implements AdapterInterface
{
    /**
     * @var string
     */
    protected $login;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var string
     */
    protected $authMethod;

    /**
     * @param EntityManager $entityManager
     * @param string $authMethod
     */
    public function __construct(EntityManager $entityManager, string $authMethod)
    {
        $this->entityManager = $entityManager;
        $this->authMethod = $authMethod;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $login
     * @return UserAdapter
     */
    public function setLogin(string $login): UserAdapter
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return UserAdapter
     */
    public function setPassword(string $password): UserAdapter
    {
        $this->password = $password;
        return $this;
    }

    public function authenticate()
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            $this->authMethod => $this->login,
        ]);

        if(!$user){
            die('user not found');
        }

    }


}