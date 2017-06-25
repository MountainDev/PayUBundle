<?php
/**
 * Created by radnok <alfaro.konrad@gmail.com>.
 * Date: 21.06.2017
 */

namespace RadnoK\PayUBundle\Payment;

use OpenPayU_Configuration;
use Psr\Log\InvalidArgumentException;
use RadnoK\PayUBundle\DBAL\Types\PlanTypeType;

class PaymentFactory implements PaymentFactoryInterface
{
    public function __construct(string $environment, string $clientId, string $clientSecret)
    {
        OpenPayU_Configuration::setEnvironment($environment);
        OpenPayU_Configuration::setOauthClientId($clientId);
        OpenPayU_Configuration::setOauthClientSecret($clientSecret);
    }

    public function makePayment($type)
    {
        switch($type) {
            case PlanTypeType::SINGLE:
                return new SinglePayment();
            case PlanTypeType::RECURRING:
                return new RecurringPayment();
            case PlanTypeType::CARD:
                return new CardPayment();
            default:
                throw new InvalidArgumentException('Invalid Payment type');
        }
    }
}
