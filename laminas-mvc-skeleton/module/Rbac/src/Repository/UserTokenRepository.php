<?php

namespace Rbac\Repository;

use Doctrine\ORM\EntityRepository;
use Rbac\Entity\UserToken;

/**
 * UserTokenRepository
 */
class UserTokenRepository extends EntityRepository
{

    /**
     * @param string $token
     * @param \DateInterval $delay
     * @return UserToken|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findActiveToken(string $token, \DateInterval $delay): ?UserToken
    {
        $limit = new \DateTime();
        $limit->sub($delay);
        $return = $this->createQueryBuilder('u');
        $return->where('u.token = :token')
            ->andWhere('u.is_active = 1')
            ->andWhere('u.date_creation >= :limitDate')
            ->setParameter('token', $token)
            ->setParameter('limitDate', $limit)
        ;

        return $return->getQuery()->getOneOrNullResult();
    }

}