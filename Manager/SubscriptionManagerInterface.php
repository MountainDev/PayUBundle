<?php
/**
 * Created by radnok <alfaro.konrad@gmail.com>.
 * Date: 20.06.2017
 */

namespace RadnoK\PayUBundle\Manager;


use RadnoK\PayUBundle\Model\SubscriptionInterface;

interface SubscriptionManagerInterface
{
    public function create(): SubscriptionInterface;

    public function update(SubscriptionInterface $subscription);

    public function getClass();
}