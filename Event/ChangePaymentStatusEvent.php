<?php
/**
 * Created by radnok <alfaro.konrad@gmail.com>.
 * Date: 25.06.2017
 */

namespace RadnoK\PayUBundle\Event;

use RadnoK\PayUBundle\Model\OrderInterface;
use Symfony\Component\EventDispatcher\Event;

class ChangePaymentStatusEvent extends Event
{
    /**
     * @var OrderInterface
     */
    private $order;

    /**
     * @var string
     */
    private $status;

    public function __construct(OrderInterface $order, string $status)
    {
        $this->order = $order;
        $this->status = $status;
    }

    public function getOrder(): OrderInterface
    {
        return $this->order;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
