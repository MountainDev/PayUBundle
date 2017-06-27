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

    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $tokenType;

    public function __construct(
        PlanInterface $plan,
        SubscriberInterface $subscriber,
        string $token,
        string $tokenType
    ) {
        $this->plan = $plan;
        $this->subscriber = $subscriber;
        $this->token = $token;
        $this->tokenType = $tokenType;
    }

    public function getPlan(): PlanInterface
    {
        return $this->plan;
    }

    public function getSubscriber(): SubscriberInterface
    {
        return $this->subscriber;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getTokenType(): string
    {
        return $this->tokenType;
    }
}
