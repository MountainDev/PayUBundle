<?php

namespace RadnoK\PayUBundle\Event;

use Symfony\Component\EventDispatcher\Event;

abstract class NewPaymentEvent extends Event
{
    private $response;

    public function setResponse($response)
    {
        $this->response = $response;

        return $this;
    }

    public function getResponse()
    {
        return $this->response;
    }
}
