<?php

namespace RadnoK\PayUBundle\Payment;

use OpenPayU_Configuration;
use RadnoK\PayUBundle\Model\OrderInterface;
use RadnoK\PayUBundle\Model\SubscriberInterface;
use RadnoK\PayUBundle\Model\SubscriptionInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class RecurringPayment
{
    public function getOrderData(OrderInterface $order, string $token, string $notifyUrl): array
    {
        /** @var SubscriberInterface $subscriber */
        $subscriber = $order->getSubscriber();

        return [
            'notifyUrl'     => $notifyUrl,
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
