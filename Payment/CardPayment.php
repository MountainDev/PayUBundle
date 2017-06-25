<?php

namespace RadnoK\PayUBundle\Payment;

use OpenPayU_Configuration;
use RadnoK\PayUBundle\Model\OrderInterface;
use RadnoK\PayUBundle\Model\SubscriberInterface;
use RadnoK\PayUBundle\Model\SubscriptionInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

final class CardPayment
{
    public function getOrderData(OrderInterface $order, string $token, string $notifyUrl): array
    {
        /** @var SubscriberInterface $subscriber */
        $subscriber = $order->getSubscriber();

        return [
            'notifyUrl'     => $notifyUrl,
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
