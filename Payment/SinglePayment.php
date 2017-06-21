<?php

namespace RadnoK\PayUBundle\Payment;

use OpenPayU_Configuration;
use RadnoK\PayUBundle\Model\OrderInterface;
use RadnoK\PayUBundle\Model\SubscriberInterface;

final class SinglePayment
{
    /**
     * @param OrderInterface $order
     * @param $target
     * @param $notify
     * @return array
     */
    public function getOrderData(OrderInterface $order, $target, $notify): array
    {
        /** @var SubscriberInterface $subscriber */
        $subscriber = $order->getSubscriber();

        return [
            'continueUrl'   => $target,
            'notifyUrl'     => $notify,
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
                'phone'         => $subscriber->getPhoneNumber(),
                'firstName'     => $subscriber->getFirstName(),
                'lastName'      => $subscriber->getLastName(),
            ],
            'settings'      => [
                'invoiceDisabled' => 'true',
            ],
        ];
    }
}
