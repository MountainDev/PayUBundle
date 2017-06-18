<?php

namespace RadnoK\PayUBundle\Entity;

interface SubscriberInterface
{
    public function getId();

    public function getEmail();

    public function getPhoneNumber();

    public function getFirstName();

    public function getLastName();
}