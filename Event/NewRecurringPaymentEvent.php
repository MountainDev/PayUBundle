<?php

namespace RadnoK\PayUBundle\Event;

use FOS\UserBundle\Model\UserInterface;
use RadnoK\PayUBundle\Entity\SubscriberInterface;

class NewRecurringPaymentEvent extends NewPaymentEvent
{
    private $amount;

    private $user;

    public function __construct($amount, SubscriberInterface $user)
    {
        $this->amount = $amount;
        $this->user = $user;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getUser()
    {
        return $this->user;
    }
}
