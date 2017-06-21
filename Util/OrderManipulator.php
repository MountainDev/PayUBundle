<?php
/**
 * Created by radnok <alfaro.konrad@gmail.com>.
 * Date: 19.06.2017
 */

namespace RadnoK\PayUBundle\Util;

use RadnoK\PayUBundle\Manager\OrderManagerInterface;
use RadnoK\PayUBundle\Model\OrderInterface;
use RadnoK\PayUBundle\Model\PlanInterface;
use RadnoK\PayUBundle\Model\SubscriberInterface;
use RadnoK\PayUBundle\Model\SubscriptionInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class OrderManipulator
{
    /**
     * @var OrderManagerInterface
     */
    private $orderManager;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(
        OrderManagerInterface $orderManager,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->orderManager = $orderManager;
        $this->dispatcher = $eventDispatcher;
    }

    public function create(SubscriptionInterface $subscription)
    {
        /** @var PlanInterface $plan */
        $plan = $subscription->getPlan();

        $description = $plan->getName();

        $order = $this->orderManager->create();
        $order->setSubscriber($subscription->getSubscriber());
        $order->setAmount($plan->getPrice());
        $order->setDescription(null === $description ? uniqid('order_') : $description);

        $this->orderManager->update($order);

        return $order;
    }

    public function update(OrderInterface $order)
    {
        $this->orderManager->update($order);
    }
}
