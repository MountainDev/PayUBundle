<?php

namespace RadnoK\PayUBundle\Repository;

use Doctrine\ORM\EntityManager;

abstract class OrderRepository
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function findOneByOrder($id)
    {
        $builder = $this->entityManager->createQueryBuilder();

        $query = $builder
            ->select('plan')
            ->where('order.orderId = :id')
            ->setParameter('id', $id)
        ;

        return $query->getQuery()->getResult();
    }
}
