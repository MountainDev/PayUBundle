<?php

namespace RadnoK\PayUBundle\Model;

interface OrderInterface
{
    const COMPLETED = 'COMPLETED';

    const CANCELED = 'CANCELED';

    public function getId();

    public function getOrderId();

    public function getAmount();

    public function getDescription();

    public function getIsPaid();

    public function getFromSubscription();

    public function getSubscriber();
}