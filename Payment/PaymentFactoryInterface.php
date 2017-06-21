<?php
/**
 * Created by radnok <alfaro.konrad@gmail.com>.
 * Date: 21.06.2017
 */

namespace RadnoK\PayUBundle\Payment;

interface PaymentFactoryInterface
{
    public function makePayment($type);
}