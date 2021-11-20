<?php

namespace Rbac\Repository;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;

/**
 *
 */
class PermissionRepository extends EntityRepository
{

    /**
     * @param array $names
     * @return Collection|null
     */
    public function getByPermissionNames(array $names): ?array
    {

        $qb = $this->createQueryBuilder('p');
        $qb->where('p.name in (:names)');
        $qb->setParameter('names', $names);

        return $qb->getQuery()->getResult();
    }

}