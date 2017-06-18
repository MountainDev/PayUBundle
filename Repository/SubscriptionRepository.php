<?php

namespace RadnoK\PayUBundle\Repository;

use Doctrine\ORM\EntityRepository;

class SubscriptionRepository extends EntityRepository
{
    public function findAllChargeable()
    {
        $builder = $this->createQueryBuilder('subscription');

        $query = $builder
            ->where($builder->expr()->isNotNull('subscription.token'))
            ->andWhere('subscription.chargesFailed <= 10')
            ->andWhere('subscription.lastPaymentSuccess = :null')
            ->andWhere('subscription.lastPaymentAttempt < :now')
        ;

        $query->setParameters(
            [
                'null' => null,
                'now' => (new \DateTime())->format('Y-m-d'),
            ]
        );

        return $query->getQuery()->getResult();
    }
}
