<?php

namespace RadnoK\PayUBundle\Model;

interface OrderInterface
{
    const COMPLETED = 'COMPLETED';

    const CANCELED = 'CANCELED';

    public function getId(): int;

    public function getOrderId(): ?string;

    public function setOrderId(string $orderId);

    public function getAmount(): ?float;

    public function setAmount(float $amount);

    public function getDescription(): ?string;

    public function setDescription(string $description);

    public function getIsPaid(): bool;

    public function setIsPaid(bool $isPaid);

    public function getFromSubscription(): bool;

    public function setFromSubscription(bool $fromSubscription);

    public function getSubscriber(): SubscriberInterface;

    public function setSubscriber(SubscriberInterface $subscriber);
}