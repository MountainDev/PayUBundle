<?php

namespace RadnoK\PayUBundle\Event;

use RadnoK\PayUBundle\Model\OrderInterface;
use Symfony\Component\EventDispatcher\Event;

abstract class NewPaymentEvent extends Event
{
    private $response;

    private $order;

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse($response)
    {
        $this->response = $response;

        return $this;
    }

    public function getOrder(): ?OrderInterface
    {
        return $this->order;
    }

    public function setOrder(OrderInterface $order)
    {
        $this->order = $order;

        return $this;
    }
}
