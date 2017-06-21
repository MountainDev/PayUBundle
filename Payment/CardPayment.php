<?php

namespace RadnoK\PayUBundle\Payment;

use OpenPayU_Configuration;
use RadnoK\PayUBundle\Model\OrderInterface;
use RadnoK\PayUBundle\Model\SubscriberInterface;
use RadnoK\PayUBundle\Model\SubscriptionInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

final class CardPayment
{
    /**
     * @param SubscriptionInterface $subscription
     * @return OrderInterface
     */
    private function createOrderForSubscription(SubscriptionInterface $subscription)
    {
        $order = new Order();
        $order->setSubscriber($subscription->getSubscriber());
        $order->setAmount($subscription->getAmount());

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $order;
    }

    /**
     * @param OrderInterface $order
     * @param $token
     * @return array
     */
    private function buildRequestData(OrderInterface $order, $token)
    {
        /** @var SubscriberInterface $subscriber */
        $subscriber = $order->getSubscriber();

        return [
            'notifyUrl'     => $this->router->generate('radnok_payu_payments_notify'),
            'recurring'     => 'STANDARD',
            'customerIp'    => '127.0.0.1',
            'merchantPosId' => OpenPayU_Configuration::getOauthClientId(),
            'description'   => $order->getDescription(),
            'currencyCode'  => 'PLN',
            'totalAmount'   => $order->getAmount() * 100,
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
                'phone'         => $subscriber->getPhoneNumber(),
                'firstName'     => $subscriber->getFirstName(),
                'lastName'      => $subscriber->getLastName(),
            ],
            'payMethods'    => [
                'payMethod' => [
                    'value' => $token,
                    'type'  => 'CARD_TOKEN'
                ]
            ]
        ];
    }
}
