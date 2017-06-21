<?php
/**
 * Created by radnok <alfaro.konrad@gmail.com>.
 * Date: 20.06.2017
 */

namespace RadnoK\PayUBundle\ValueObject;


use RadnoK\PayUBundle\Model\OrderInterface;

final class SingleOrderData
{
    /**
     * @var OrderInterface
     */
    private $order;

    /**
     * @var string
     */
    private $notifyUrl;

    /**
     * @var string
     */
    private $continueUrl;

    public function __construct(
        OrderInterface $order,
        string $notifyUrl,
        string $continueUrl
    ) {
        $this->order = $order;
        $this->notifyUrl = $notifyUrl;
        $this->continueUrl = $continueUrl;
    }

    public function getOrder(): OrderInterface
    {
        return $this->order;
    }

    public function getNotifyUrl(): string
    {
        return $this->notifyUrl;
    }

    public function getContinueUrl(): string
    {
        return $this->continueUrl;
    }

}
