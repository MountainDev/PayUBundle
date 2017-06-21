<?php
/**
 * Created by radnok <alfaro.konrad@gmail.com>.
 * Date: 20.06.2017
 */

namespace RadnoK\PayUBundle\ValueObject;

use RadnoK\PayUBundle\Model\OrderInterface;

final class RecurringOrderData
{
    /**
     * @var OrderInterface
     */
    private $order;

    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $tokenType;

    public function __construct(OrderInterface $order, string $token, string $tokenType)
    {
        $this->order = $order;
        $this->token = $token;
        $this->tokenType = $tokenType;
    }

    public function getOrder(): OrderInterface
    {
        return $this->order;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getTokenType(): string
    {
        return $this->tokenType;
    }
}
