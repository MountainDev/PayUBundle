<?php

namespace RadnoK\PayUBundle\EventListener;

use RadnoK\PayUBundle\DBAL\Types\PlanType;
use RadnoK\PayUBundle\Event\ChargeCardsEvent;
use RadnoK\PayUBundle\Event\NewRecurringPaymentEvent;
use RadnoK\PayUBundle\Event\NewSinglePaymentEvent;
use RadnoK\PayUBundle\Model\OrderInterface;
use RadnoK\PayUBundle\Model\SubscriberInterface;
use RadnoK\PayUBundle\Model\SubscriptionInterface;
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

class PaymentListener implements EventSubscriberInterface
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
            RadnoKPayUEvents::CHARGE_CARDS    => 'onCardCharge',
        ];
    }

    /**
     * @param NewSinglePaymentEvent $event
     */
    public function onSinglePayment(NewSinglePaymentEvent $event)
    {
        /** @var SubscriptionInterface $subscription */
        $subscription = $this->subscriptionManipulator->create(
            $event->getPlan(),
            $event->getSubscriber()
        );

        /** @var OrderInterface $order */
        $order = $this->orderManipulator->create($subscription);

        /** @var SinglePayment $singlePayment */
        $singlePayment = $this->paymentFactory->makePayment(PlanType::SINGLE);

        $continueUrl = $this->router->generate('radnok_payu_payments_continue', [], UrlGeneratorInterface::ABSOLUTE_URL);
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
        $subscription = $this->subscriptionManipulator->create(
            $event->getPlan(),
            $event->getSubscriber()
        );

        /** @var OrderInterface $order */
        $order = $this->orderManipulator->create($subscription);

        /** @var RecurringPayment $recurringPayment */
        $recurringPayment = $this->paymentFactory->makePayment(PlanType::RECURRING);

        $request = OpenPayU_Order::create(
            $recurringPayment->getOrderData($order, $event->getToken(), $event->getTokenType())
        );

        $response = $request->getResponse();

        if ($response->status->statusCode === 'SUCCESS') {
            $this->makePaymentAttempt($subscription, $order, $response);
        }

        if ($response->status->statusCode === 'WARNING_CONTINUE_3DS') {
            $this->makePaymentAttempt($subscription, $order, $response);

            $redirectUri = $response->redirectUri;
        }

        $subscriber->setSubscription($subscription);

        $this->entityPersistAndFlush($subscriber);

        if (isset($redirectUri)) {
            $response = [
                'redirectTo' => $redirectUri,
            ];
        } else {
            $response = [
                'status'    => 'ok',
                'url'       => $request->server->get('HTTP_ORIGIN'),
                'page'      => $request->get('page'),
            ];
        }

        $event->setResponse($response);
    }

    public function onCardCharge(ChargeCardsEvent $event)
    {
        $subscriptions = $this->subscriptionManipulator->getAwaitingSubscriptions();

        /** @var SubscriptionInterface $subscription */
        foreach ($subscriptions as $subscription) {
            /** @var OrderInterface $order */
            $order = $this->createOrderForSubscription($subscription);

            $request = OpenPayU_Order::create(
                $this->getCardOrderData(
                    $order, $subscription->getToken())
            );

            $response = $request->getResponse();

            $subscription->setLastPaymentAttempt(new \DateTime());
            $this->subscriptionManager->update($subscription);

            $order->setOrderId($response->orderId);
            $this->orderManager->update($order);
        }
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
        $this->subscriptionManager->update($subscription);

        $order->setOrderId($response->orderId);
        $this->orderManager->update($order);
    }

    /**
     * @param SubscriptionInterface $subscription
     * @return OrderInterface
     */
    private function createOrderForSubscription(SubscriptionInterface $subscription): OrderInterface
    {
        /** @var OrderInterface $order */
        $order = $this->orderManipulator->createOrder();

        $order->setSubscriber($subscription->getSubscriber());
        $order->setAmount($subscription->getAmount());

        $this->orderManipulator->update($order);

        // @TODO Event

        return $order;
    }

    private function getSingleOrderData(array $parameters): array
    {
        $order = $parameters['order'];

        /** @var SubscriberInterface $subscriber */
        $subscriber = $order->getSubscriber();

        return [
            'continueUrl'   => 'asd',
            'notifyUrl'     => '123',
            'customerIp'    => '127.0.0.1',
            'merchantPosId' => OpenPayU_Configuration::getOauthClientId(),
            'description'   => $order->getDescription(),
            'currencyCode'  => 'PLN',
            'totalAmount'   => $order->getAmount() * 100,
            'extOrderId'    => uniqid('', true),
            'products'      => [
                [
                    'name'      => $order->getDescription(),
                    'unitPrice' => $order->getAmount() * 100,
                    'quantity'  => 1
                ]
            ],
            'buyer'         => [
                'extCustomerId' => $subscriber->getId(),
                'email'         => $subscriber->getEmail(),
                'phone'         => $subscriber->getCompany()->getPhone(),
                'firstName'     => $subscriber->getName(),
                'lastName'      => $subscriber->getSurname(),
            ],
            'settings'      => [
                'invoiceDisabled' => 'true',
            ],
        ];
    }

    public function getRecurringOrderData(array $parameters)
    {
        $order = $parameters['order'];
        $token = $parameters['token'];

        /** @var SubscriberInterface $subscriber */
        $subscriber = $order->getSubscriber();

        return [
            'notifyUrl'     => 'asd',
            'customerIp'    => '127.0.0.1',
            'merchantPosId' => OpenPayU_Configuration::getOauthClientId(),
            'description'   => $order->getDescription(),
            'currencyCode'  => 'PLN',
            'totalAmount'   => $order->getAmount() * 100,
            'extOrderId'    => $order->getId(),
            'products'      => [
                [
                    'name'      => $order->getDescription(),
                    'unitPrice' => $order->getAmount() * 100,
                    'quantity'  => 1
                ]
            ],
            'buyer' => [
                'extCustomerId' => $subscriber->getId(),
                'email'         => $subscriber->getEmail(),
                'phone'         => $subscriber->getPhoneNumber(),
                'firstName'     => $subscriber->getFirstName(),
                'lastName'      => $subscriber->getLastName(),
            ],
            'payMethods'    => [
                'payMethod' => [
                    'type'  => 'CARD_TOKEN',
                    'value' => $token,
                ],
            ],
            'settings' => [
                'invoiceDisabled' => 'true',
            ],
        ];
    }
}
