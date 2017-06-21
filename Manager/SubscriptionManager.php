<?php
/**
 * Created by radnok <alfaro.konrad@gmail.com>.
 * Date: 20.06.2017
 */

namespace RadnoK\PayUBundle\Manager;


abstract class SubscriptionManager implements SubscriptionManagerInterface
{
    public function createOrder()
    {
        $class = $this->getClass();

        return new $class;
    }
}
