<?php

namespace RadnoK\PayUBundle\Event;

use RadnoK\PayUBundle\Model\PlanInterface;
use RadnoK\PayUBundle\Model\SubscriberInterface;

class NewSinglePaymentEvent extends NewPaymentEvent
{
    /**
     * @var PlanInterface
     */
    private $plan;

    /**
     * @var SubscriberInterface
     */
    private $subscriber;

    /**
     * @var string
     */
    private $continueUrl;

    public function __construct(PlanInterface $plan, SubscriberInterface $subscriber, string $continueUrl)
    {
        $this->plan = $plan;
        $this->subscriber = $subscriber;
        $this->continueUrl = $continueUrl;
    }

    public function getPlan()
    {
        return $this->plan;
    }

    public function getSubscriber()
    {
        return $this->subscriber;
    }

    public function getContinueUrl(): string
    {
        return $this->continueUrl;
    }
}
