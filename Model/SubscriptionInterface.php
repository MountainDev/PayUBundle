<?php

namespace RadnoK\PayUBundle\Model;

interface SubscriptionInterface
{
    public function getId();

    public function getToken();

    public function setToken($token);

    public function getLastPaymentAttempt();

    public function setLastPaymentAttempt(\DateTime $dateTime);

    public function getLastPaymentSuccess();

    public function setLastPaymentSuccess(\DateTime $dateTime);

    public function getChargesFailed();

    public function addChargesFailed();

    public function setChargesFailed(int $number);

    public function getPlan(): PlanInterface;

    public function setPlan(PlanInterface $plan);

    public function getSubscriber(): SubscriberInterface;

    public function setSubscriber(SubscriberInterface $subscriber);
}