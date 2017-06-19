<?php

namespace RadnoK\PayUBundle\Model;

interface SubscriptionInterface
{
    public function getId();

    public function getSubscriber();

    public function setSubscriber(SubscriberInterface $subscriber);
}