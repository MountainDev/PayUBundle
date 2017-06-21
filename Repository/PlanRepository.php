<?php

namespace RadnoK\PayUBundle\Repository;

use Doctrine\ORM\EntityRepository;

abstract class PlanRepository extends EntityRepository
{
    public function findAllActive()
    {
        $builder = $this->createQueryBuilder('plan');

        $query = $builder
            ->where('plan.active = true')
        ;

        return $query->getQuery()->getResult();
    }

    public function getAllForCode($code)
    {
        $builder = $this->createQueryBuilder('plan');

        $query = $builder
            ->where('plan.active = true')
            ->andWhere($builder->expr()->like('plan.code', ':code'))
        ;

        $query->setParameter('code', '%'.$code.'_%');

        return $query;
    }
}
