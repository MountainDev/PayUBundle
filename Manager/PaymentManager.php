<?php

namespace RadnoK\PayUBundle\Manager;

use RadnoK\PayUBundle\Model\OrderInterface;
use RadnoK\PayUBundle\Payment\RecurringPayment;
use RadnoK\PayUBundle\Payment\SinglePayment;

class PaymentManager
{
    /**
     * @var SinglePayment
     */
    protected $singlePayment;

    /**
     * @var RecurringPayment
     */
    protected $recurringPayment;

    public function __construct(
        SinglePayment $singlePayment,
        RecurringPayment $recurringPayment
    ) {
        $this->singlePayment = $singlePayment;
        $this->recurringPayment = $recurringPayment;
    }

    public function singleOrderData(OrderInterface $order, string $target, string $notify)
    {
        return $this->singlePayment->getOrderData($order, $target, $notify);
    }

    public function recurringOrderData(OrderInterface $order, string $token, string $notify)
    {
        return $this->recurringPayment->getOrderData($order, $token, $notify);
    }

    /**
     * Fetch all awaiting subscriptions for charge
     *
     * @return array
     */
    public function getAwaitingSubscriptions()
    {
        return $this->entityManager
            ->getRepository(Subscription::class)
            ->findAllChargeable();
    }

    public function createSubscription(PlanInterface $plan, SubscriberInterface $user)
    {
        $subscription = new Subscription();
        $subscription->setPlan($plan);
        $subscription->setSubscriber($user);

        $this->entityPersistAndFlush($subscription);

        return $subscription;
    }

    public function createOrder(Subscription $subscription)
    {
        /** @var PlanInterface $plan */
        $plan = $subscription->getPlan();

        $description = $plan->getName();

        $order = new Order();
        $order->setSubscriber($subscription->getSubscriber());
        $order->setAmount($plan->getPrice());
        $order->setDescription(null === $description ? uniqid('order_') : $description);

        $this->entityPersistAndFlush($order);

        return $order;
    }
}
