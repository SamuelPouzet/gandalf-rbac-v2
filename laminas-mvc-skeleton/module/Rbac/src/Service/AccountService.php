<?php

namespace Rbac\Service;

use Doctrine\ORM\EntityManager;
use Rbac\Entity\User;

class AccountService
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var User|null
     */
    protected $user;


    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getInstance(): ?User
    {
        if($this->user){
            return $this->user;
        }

    }



}