<?php

namespace RadnoK\PayUBundle\Event;

use FOS\UserBundle\Model\UserInterface;
use RadnoK\PayUBundle\Entity\PlanInterface;

class NewSinglePaymentEvent extends NewPaymentEvent
{
    private $plan;

    private $user;

    public function __construct(PlanInterface $plan, UserInterface $user)
    {
        $this->plan = $plan;
        $this->user = $user;
    }

    public function getPlan()
    {
        return $this->plan;
    }

    public function getUser()
    {
        return $this->user;
    }
}
