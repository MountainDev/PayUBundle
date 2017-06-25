<?php

namespace RadnoK\PayUBundle\Event;

use RadnoK\PayUBundle\Model\SubscriptionInterface;

class ChargeCardsEvent extends NewPaymentEvent
{
    private $subscription;

    public function __construct(SubscriptionInterface $subscription)
    {
        $this->subscription = $subscription;
    }

    public function getSubscription(): SubscriptionInterface
    {
        return $this->subscription;
    }


}
