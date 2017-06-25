<?php
/**
 * Created by radnok <alfaro.konrad@gmail.com>.
 * Date: 21.06.2017
 */

namespace RadnoK\PayUBundle\Widget;


final class PaymentParameters
{
    public static function generate($clientId, $siteName, $amount, $email, $currency = 'PLN', $language = 'pl')
    {
        return [
            'merchant-pos-id'   => $clientId,
            'shop-name'         => $siteName,
            'total-amount'      => $amount,
            'currency-code'     => $currency,
            'customer-language' => $language,
            'store-card'        => 'true',
            'recurring-payment' => 'true',
            'customer-email'    => $email,
            'payu-brand'        => 'false',
            'widget-mode'       => 'pay'
        ];
    }
}
