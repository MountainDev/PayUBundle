<?php

namespace RadnoK\PayUBundle\Payment;

use RadnoK\CommonBundle\Traits\EntityManagerAwareTrait;
use RadnoK\CommonBundle\Traits\RouterAwareTrait;
use RadnoK\PayUBundle\Manager\PaymentManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use OpenPayU_Configuration;

abstract class Payment
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @var PaymentManager
     */
    protected $paymentManager;

    public function __construct(
        PaymentManager $paymentManager,
        Router $router
    ) {
        $this->paymentManager = $paymentManager;
        $this->router = $router;

        OpenPayU_Configuration::setEnvironment('sandbox');
        OpenPayU_Configuration::setMerchantPosId('145227');
        OpenPayU_Configuration::setSignatureKey('13a980d4f851f3d9a1cfc792fb1f5e50');
    }
}
