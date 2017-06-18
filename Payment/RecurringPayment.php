<?php

namespace RadnoK\PayUBundle\Payment;

use AppBundle\Entity\User;
use RadnoK\CommonBundle\Traits\RouterAwareTrait;
use RadnoK\PayUBundle\Entity\Order;
use RadnoK\PayUBundle\Entity\Subscriber;
use RadnoK\PayUBundle\Entity\SubscriberInterface;
use RadnoK\PayUBundle\Entity\Subscription;
use RadnoK\PayUBundle\Manager\PaymentManager;
use OpenPayU_Order;
use OpenPayU_Configuration;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RecurringPayment
{
    use RouterAwareTrait;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Get all order data
     *
     * @param Order $order
     * @param Subscription $subscription
     * @param string $token
     * @return array
     */
    public function getOrderData(Order $order, $token, $notify): array
    {
        $notifyRoute = $this->router->generate($notify, [], UrlGeneratorInterface::ABSOLUTE_URL);

        /** @var SubscriberInterface $subscriber */
        $subscriber = $order->getSubscriber();

        return [
            'notifyUrl'     => $notifyRoute,
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
