<?php

namespace RadnoK\PayUBundle\Payment;

use Symfony\Component\HttpFoundation\Request;

interface PaymentInterface
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function getOrderData();
}