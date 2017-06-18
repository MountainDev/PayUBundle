<?php

namespace RadnoK\PayUBundle\Payment;

use AppBundle\Entity\User;
use RadnoK\CommonBundle\Traits\RouterAwareTrait;
use RadnoK\PayUBundle\Entity\Order;
use OpenPayU_Configuration;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SinglePayment
{
    use RouterAwareTrait;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getOrderData(Order $order, $target, $notify)
    {
        $targetRoute = $this->router->generate($target, [], UrlGeneratorInterface::ABSOLUTE_URL);
        $notifyRoute = $this->router->generate($notify, [], UrlGeneratorInterface::ABSOLUTE_URL);

        /** @var User $subscriber */
        $subscriber = $order->getSubscriber();

        return [
            'continueUrl'   => $targetRoute,
            'notifyUrl'     => $notifyRoute,
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
}
