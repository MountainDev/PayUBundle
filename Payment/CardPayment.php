<?php

namespace RadnoK\PayUBundle\Payment;

use RadnoK\PayUBundle\Entity\Order;
use RadnoK\PayUBundle\Entity\Subscription;
use OpenPayU_Order;
use OpenPayU_Configuration;

class CardPayment
{
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

    /**
     * @param Order $order
     * @param $token
     * @return array
     */
    private function buildRequestData(Order $order, $token)
    {
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
                'extCustomerId' => $order->getSubscriber()->getId(),
                'email'         => $order->getSubscriber()->getEmail(),
                'phone'         => $order->getSubscriber()->getPhoneNumber(),
                'firstName'     => $order->getSubscriber()->getFirstName(),
                'lastName'      => $order->getSubscriber()->getLastName(),
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
