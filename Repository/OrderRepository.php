<?php

namespace RadnoK\PayUBundle\Repository;

use Doctrine\ORM\EntityRepository;

class OrderRepository extends EntityRepository
{
    public function findOneByOrder($id)
    {
        $builder = $this->createQueryBuilder('order');

        $query = $builder
            ->where('order.orderId = :id')
            ->setParameter('id', $id)
        ;

        return $query->getQuery()->getResult();
    }
}
