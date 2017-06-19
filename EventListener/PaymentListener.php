<?php

namespace RadnoK\PayUBundle\EventListener;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use RadnoK\CommonBundle\Traits\EntityManagerAwareTrait;
use RadnoK\CommonBundle\Traits\RouterAwareTrait;
use RadnoK\PayUBundle\Entity\Order;
use RadnoK\PayUBundle\Entity\Plan;
use RadnoK\PayUBundle\Entity\Subscription;
use RadnoK\PayUBundle\Event\ChargeCardsEvent;
use RadnoK\PayUBundle\Event\NewRecurringPaymentEvent;
use RadnoK\PayUBundle\Event\NewSinglePaymentEvent;
use RadnoK\PayUBundle\RadnoKPayUEvents;
use RadnoK\PayUBundle\Manager\PaymentManager;
use RadnoK\PayUBundle\Service\PayUGateway;
use RadnoK\PayUBundle\Traits\PaymentManagerAwareTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use OpenPayU_Configuration;
use OpenPayU_Order;

class PaymentListener implements EventSubscriberInterface
{
    use PaymentManagerAwareTrait;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(
        PaymentManager $paymentManager,
        EntityManager $entityManager,
        string $environment,
        string $clientId,
        string $clientSecret
    ) {
        $this->paymentManager = $paymentManager;
        $this->entityManager = $entityManager;

        OpenPayU_Configuration::setEnvironment($environment);
        OpenPayU_Configuration::setOauthClientId($clientId);
        OpenPayU_Configuration::setOauthClientSecret($clientSecret);
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
        /** @var Plan $plan */
        $plan = $event->getPlan();

        /** @var User $user */
        $user = $event->getUser();

        $subscription = $this->paymentManager->createSubscription($plan, $user);
        $order = $this->paymentManager->createOrder($subscription);

        $request = OpenPayU_Order::create(
            $this->paymentManager->singleOrderData(
                $order,
                'radnok_payu_payments_continue',
                'radnok_payu_payments_notify'
            )
        );

        $response = $request->getResponse();

        $order->setOrderId($response->orderId);

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        $event->setResponse($response->redirectUri);
    }

    /**
     * @param NewRecurringPaymentEvent $event
     */
    public function onRecurringPayment(NewRecurringPaymentEvent $event)
    {
        $subscription = $this->paymentManager->createSubscription(
            $event->getAmount(),
            $event->getUser()
        );

        $order = $this->paymentManager->createOrder($subscription);

        $orderData = $this->getOrderData(
            $order,
            $event->getToken(),
            $event->getTokenType()
        );

        $requestOrder = OpenPayU_Order::create($orderData);

        $response = $requestOrder->getResponse();

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
        $subscriptions = $this->paymentManager->getAwaitingSubscriptions();

        /** @var Subscription $subscription */
        foreach ($subscriptions as $subscription) {
            /** @var Order $order */
            $order = $this->createOrderForSubscription($subscription);

            $request = OpenPayU_Order::create(
                $this->buildRequestData($order, $subscription->getToken())
            );

            $response = $request->getResponse();

            $order->setOrderId($response->orderId);
            $this->entityManager->persist($order);
            $this->entityManager->flush();

            $subscription->setLastPaymentAttempt(new \DateTime());
            $this->entityManager->persist($subscription);
            $this->entityManager->flush();
        }
    }

    /**
     * @param Subscription $subscription
     * @param Order $order
     * @param $response
     */
    private function makePaymentAttempt(Subscription $subscription, Order $order, $response)
    {
        $subscription->setToken($response->payMethods->payMethod->value);
        $subscription->setLastPaymentAttempt(new \DateTime());

        $order->setOrderId($response->orderId);

        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }

    /**
     * @param Subscription $subscription
     * @return Order
     */
    private function createOrderForSubscription(Subscription $subscription)
    {
        $order = new Order();
        $order->setSubscriber($subscription->getSubscriber());
        $order->setAmount($subscription->getAmount());

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $order;
    }
}
