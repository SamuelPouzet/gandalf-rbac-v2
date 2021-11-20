<?php

namespace Rbac\Manager;

use Doctrine\ORM\EntityManager;
use Rbac\Entity\FailedTries;

class BanManager
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

    public function addFail()
    {
        $fail = new FailedTries();

    }


}