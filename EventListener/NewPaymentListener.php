<?php

namespace RadnoK\PayUBundle\EventListener;

use RadnoK\PayUBundle\DBAL\Types\PlanTypeType;
use RadnoK\PayUBundle\Event\ChargeCardsEvent;
use RadnoK\PayUBundle\Event\NewRecurringPaymentEvent;
use RadnoK\PayUBundle\Event\NewSinglePaymentEvent;
use RadnoK\PayUBundle\Model\OrderInterface;
use RadnoK\PayUBundle\Model\SubscriberInterface;
use RadnoK\PayUBundle\Model\SubscriptionInterface;
use RadnoK\PayUBundle\Payment\CardPayment;
use RadnoK\PayUBundle\Payment\PaymentFactoryInterface;
use RadnoK\PayUBundle\Payment\RecurringPayment;
use RadnoK\PayUBundle\Payment\SinglePayment;
use RadnoK\PayUBundle\RadnoKPayUEvents;
use RadnoK\PayUBundle\Util\OrderManipulator;
use RadnoK\PayUBundle\Util\SubscriptionManipulator;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use OpenPayU_Order;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class NewPaymentListener implements EventSubscriberInterface
{
    /**
     * @var SubscriptionManipulator
     */
    private $subscriptionManipulator;

    /**
     * @var OrderManipulator
     */
    private $orderManipulator;

    /**
     * @var PaymentFactoryInterface
     */
    private $paymentFactory;

    /**
     * @var Router
     */
    private $router;

    public function __construct(
        SubscriptionManipulator $subscriptionManipulator,
        OrderManipulator $orderManipulator,
        PaymentFactoryInterface $paymentFactory,
        Router $router
    ) {
        $this->subscriptionManipulator = $subscriptionManipulator;
        $this->orderManipulator = $orderManipulator;
        $this->paymentFactory = $paymentFactory;
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return [
            RadnoKPayUEvents::NEW_SINGLE      => 'onSinglePayment',
            RadnoKPayUEvents::NEW_RECURRING   => 'onRecurringPayment',
        ];
    }

    /**
     * @param NewSinglePaymentEvent $event
     */
    public function onSinglePayment(NewSinglePaymentEvent $event)
    {
        /** @var OrderInterface $order */
        $order = $this->orderManipulator->create(
            $event->getSubscriber(),
            $event->getPlan()->getPrice(),
            $event->getPlan()->getName()
        );

        /** @var SinglePayment $singlePayment */
        $singlePayment = $this->paymentFactory->makePayment(PlanTypeType::SINGLE);

        $continueUrl = $this->router->generate($event->getContinueUrl(), [], UrlGeneratorInterface::ABSOLUTE_URL);
        $notifyUrl = $this->router->generate('radnok_payu_payments_notify', [], UrlGeneratorInterface::ABSOLUTE_URL);

        $request = OpenPayU_Order::create(
            $singlePayment->getOrderData($order, $continueUrl, $notifyUrl)
        );

        $response = $request->getResponse();

        $order->setOrderId($response->orderId);

        $this->orderManipulator->update($order);
        $event->setResponse($response->redirectUri);
    }

    /**
     * @param NewRecurringPaymentEvent $event
     */
    public function onRecurringPayment(NewRecurringPaymentEvent $event)
    {
        /** @var SubscriberInterface $subscriber */
        $subscriber = $event->getSubscriber();

        /** @var SubscriptionInterface $subscription */
        $subscription = $this->subscriptionManipulator->create($event->getPlan(), $subscriber);

        /** @var OrderInterface $order */
        $order = $this->orderManipulator->create(
            $subscriber,
            $subscription->getPlan()->getPrice(),
            $subscription->getPlan()->getName()
        );

        /** @var RecurringPayment $recurringPayment */
        $recurringPayment = $this->paymentFactory->makePayment(PlanTypeType::RECURRING);

        $request = OpenPayU_Order::create(
            $recurringPayment->getOrderData($order, $event->getToken(), $event->getTokenType())
        );

        $response = $request->getResponse();

        $this->makePaymentAttempt($subscription, $order, $response);

        $subscriber->setSubscription($subscription);
        $this->subscriberManipulator->update($subscriber);

        $event->setResponse($response->redirectUri);
    }

    public function onCardCharge(ChargeCardsEvent $event)
    {
        $subscription = $event->getSubscription();

        /** @var CardPayment $paymentFactory */
        $paymentFactory = $this->paymentFactory->makePayment(PlanTypeType::CARD);

        $notifyUrl = $this->router->generate('radnok_payu_payments_notify', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $order = $this->createOrderForSubscription($subscription);

        $request = OpenPayU_Order::create(
            $paymentFactory->getOrderData($order, $subscription->getToken(), $notifyUrl)
        );

        $response = $request->getResponse();

        $subscription->setLastPaymentAttempt(new \DateTime());
        $this->subscriptionManipulator->update($subscription);

        $order->setOrderId($response->orderId);
        $this->orderManipulator->update($order);

        $event->setResponse($response);
    }

    /**
     * @param SubscriptionInterface $subscription
     * @param OrderInterface $order
     * @param $response
     */
    private function makePaymentAttempt(
        SubscriptionInterface $subscription,
        OrderInterface $order,
        $response
    ) {
        $subscription->setToken($response->payMethods->payMethod->value);
        $subscription->setLastPaymentAttempt(new \DateTime());
        $this->subscriptionManipulator->update($subscription);

        $order->setOrderId($response->orderId);
        $this->orderManipulator->update($order);
    }

    /**
     * @param SubscriptionInterface $subscription
     * @return OrderInterface
     */
    private function createOrderForSubscription(SubscriptionInterface $subscription): OrderInterface
    {
        /** @var OrderInterface $order */
        $order = $this->orderManipulator->create(
            $subscription->getSubscriber(),
            $subscription->getPlan()->getPrice(),
            $subscription->getPlan()->getName()
        );

        $this->orderManipulator->update($order);

        return $order;
    }

}
