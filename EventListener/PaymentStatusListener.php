<?php
/**
 * Created by radnok <alfaro.konrad@gmail.com>.
 * Date: 25.06.2017
 */

namespace RadnoK\PayUBundle\EventListener;

use RadnoK\PayUBundle\Event\ChangePaymentStatusEvent;
use RadnoK\PayUBundle\Model\SubscriberInterface;
use RadnoK\PayUBundle\Model\SubscriptionInterface;
use RadnoK\PayUBundle\RadnoKPayUEvents;
use RadnoK\PayUBundle\Util\SubscriptionManipulator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PaymentStatusListener implements EventSubscriberInterface
{
    /**
     * @var SubscriptionManipulator
     */
    private $subscriptionManipulator;

    public function __construct(SubscriptionManipulator $subscriptionManager)
    {
        $this->subscriptionManipulator = $subscriptionManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            RadnoKPayUEvents::PAYMENT_PENDING   => 'onPaymentPending',
            RadnoKPayUEvents::PAYMENT_COMPLETED => 'onPaymentCompleted',
            RadnoKPayUEvents::PAYMENT_CANCELED  => 'onPaymentCanceled',
        ];
    }

    public function onPaymentPending(ChangePaymentStatusEvent $event)
    {

    }

    public function onPaymentCompleted(ChangePaymentStatusEvent $event)
    {
        /** @var SubscriberInterface $subscriber */
        $subscriber = $event->getOrder()->getSubscriber();

        if ($subscription = $subscriber->getSubscription()) {
            /** @var SubscriptionInterface $subscription */
            $subscription->setLastPaymentSuccess(new \DateTime());
            $subscription->setChargesFailed(0);

            $this->subscriptionManipulator->update($subscription);
        }
    }

    public function onPaymentCanceled(ChangePaymentStatusEvent $event)
    {
        /** @var SubscriberInterface $subscriber */
        $subscriber = $event->getOrder()->getSubscriber();

        if ($subscription = $subscriber->getSubscription()) {
            /** @var SubscriptionInterface $subscription */
            $subscription->addChargesFailed();

            $this->subscriptionManipulator->update($subscription);
        }
    }
}
