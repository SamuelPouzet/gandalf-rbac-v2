<?php

namespace Rbac\Service;

use Doctrine\ORM\EntityManager;
use Laminas\Authentication\Storage\Session;
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
     * @var Session
     */
    protected $session;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager, Session $session)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
    }

    public function hasIdentity(): bool
    {
        return ! $this->session->isEmpty();
    }

    public function getIdentity(): bool
    {
        return $this->session->read();
    }

    public function getInstance(): ?User
    {
        if(!$this->hasIdentity()){
            return null;
        }

        if($this->user){
            return $this->user;
        }

        $this->user = $this->entityManager->getRepository(User::class)->find($this->getIdentity());
        return $this->user;

    }



}