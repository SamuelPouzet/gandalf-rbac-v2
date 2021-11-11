<?php

namespace Rbac\Manager;

use Doctrine\ORM\EntityManager;
use Laminas\Config\Processor\Token;
use Rbac\Entity\User;
use Rbac\Entity\UserToken;

class TokenManager
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


    public function createToken(User $user): UserToken
    {
        $token = uniqid('sampouzet-', true);
        if($user->isFlushed()){
            //si l'user n'est pas en base, il ne peut théoriquement pas avoir de token assigné
            $this->eraseOthersTokenForUser($user);
        }
        $entity = new UserToken();
        $entity->setUser($user);
        $entity->setDateCreation(new \DateTimeImmutable());
        $entity->setToken($token);
        $entity->setIsActive(true);

        return $entity;

    }

    protected function eraseOthersTokenForUser(User $user): void
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->update(UserToken::class, 't');
        $qb->set('t.is_active', '0');
        $qb->where('t.user = :user');
        $qb->setParameter('user', $user);

        $qb->getQuery()->execute();
    }

    public function findToken(string $token): ?UserToken
    {

        //on nettoie les token de plus de 48h qui sont périmés
        $this->eraseOldToken();

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('t');
        $qb->from(UserToken::class, 't');
        $qb->where('t.token = :token');
        $qb->andWhere('t.is_active = 1');
        $qb->setParameter('token', $token);
        $qb->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }

    protected function eraseOldToken(): void
    {
        $date = new \DateTime();
        $interval = new \DateInterval('PT48H');
        $date->sub($interval);

        $qb = $this->entityManager->createQueryBuilder();
        $qb->update(UserToken::class, 't');
        $qb->set('t.is_active', '0');
        $qb->where('t.date_creation <= :date');
        $qb->setParameter('date', $date);

        $qb->getQuery()->execute();
    }
}