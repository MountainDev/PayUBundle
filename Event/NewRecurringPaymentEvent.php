<?php

namespace RadnoK\PayUBundle\Event;

use RadnoK\PayUBundle\Model\PlanInterface;
use RadnoK\PayUBundle\Model\SubscriberInterface;

class NewRecurringPaymentEvent extends NewPaymentEvent
{
    /**
     * @var PlanInterface
     */
    private $plan;

    /**
     * @var SubscriberInterface
     */
    private $subscriber;

    public function __construct(
        PlanInterface $plan,
        SubscriberInterface $subscriber
    ) {
        $this->plan = $plan;
        $this->subscriber = $subscriber;
    }

    public function getPlan(): PlanInterface
    {
        return $this->plan;
    }

    public function getSubscriber(): SubscriberInterface
    {
        return $this->subscriber;
    }
}
