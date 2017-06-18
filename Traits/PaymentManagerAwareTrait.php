<?php

namespace RadnoK\PayUBundle\Traits;

use RadnoK\PayUBundle\Manager\PaymentManager;

trait PaymentManagerAwareTrait
{
    protected $paymentManager;

    public function setPaymentManager(PaymentManager $paymentManager)
    {
        $this->paymentManager = $paymentManager;
    }

    public function getPaymentManager()
    {
        return $this->paymentManager;
    }
}
