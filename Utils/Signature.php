<?php

namespace RadnoK\PayUBundle\Utils;

class Signature
{
    /**
     * Attributes of PayU form
     *
     * @var array
     */
    private static $attributes = [
        'merchant-pos-id',
        'shop-name',
        'total-amount',
        'currency-code',
        'customer-language',
        'customer-email',
        'store-card',
        'payu-brand',
        'widget-mode',
    ];

    /**
     * Generate SIG from given form array
     *
     * @param array $parameters
     * @return string
     */
    public static function generate(array $parameters, string $secretKey): string
    {
        if (empty($parameters)) {
            return '';
        }

        $signature = '';

        sort(self::$attributes);

        foreach (self::$attributes as $attribute) {
            $signature .= $parameters[$attribute];
        }

        $signature .= $secretKey;

        return hash('sha256', $signature);
    }
}
