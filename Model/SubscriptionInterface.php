<?php

namespace RadnoK\PayUBundle\Entity;

interface SubscriptionInterface
{
    public function getId();

    public function getSubscriber();

    public function setSubscriber(SubscriberInterface $subscriber);
}