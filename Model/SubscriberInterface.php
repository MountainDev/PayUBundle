<?php

namespace RadnoK\PayUBundle\Model;

interface SubscriberInterface
{
    public function getId();

    public function getEmail();

    public function getPhoneNumber();

    public function getFirstName();

    public function getLastName();

    public function getSubscription(): ?SubscriptionInterface;

    public function setSubscription(SubscriptionInterface $subscription);
}