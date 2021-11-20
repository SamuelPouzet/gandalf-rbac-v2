<?php

namespace Rbac\Repository;

use Doctrine\ORM\EntityRepository;

class RoleRepository extends EntityRepository
{
    /**
     * @param array $names
     * @return array|null
     */
    public function getByRoleNames(array $names): ?array
    {

        $qb = $this->createQueryBuilder('r');
        $qb->where('r.name in (:names)');
        $qb->setParameter('names', $names);

        return $qb->getQuery()->getResult();
    }
}