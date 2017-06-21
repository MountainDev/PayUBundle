<?php
/**
 * Created by radnok <alfaro.konrad@gmail.com>.
 * Date: 19.06.2017
 */

namespace RadnoK\PayUBundle\Util;


use RadnoK\PayUBundle\Manager\SubscriptionManagerInterface;
use RadnoK\PayUBundle\Model\PlanInterface;
use RadnoK\PayUBundle\Model\SubscriberInterface;
use RadnoK\PayUBundle\Model\SubscriptionInterface;

class SubscriptionManipulator
{
    /**
     * @var SubscriptionManagerInterface
     */
    private $subscriptionManager;

    public function __construct(SubscriptionManagerInterface $subscriptionManager)
    {
        $this->subscriptionManager = $subscriptionManager;
    }

    public function create(PlanInterface $plan, SubscriberInterface $subscriber)
    {
        /** @var SubscriptionInterface $subscription */
        $subscription = $this->subscriptionManager->create();

        $subscription->setPlan($plan);
        $subscription->setSubscriber($subscriber);

        $this->subscriptionManager->update($subscription);

        return $subscription;
    }
}
