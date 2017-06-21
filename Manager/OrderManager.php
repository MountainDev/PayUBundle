<?php
/**
 * Created by radnok <alfaro.konrad@gmail.com>.
 * Date: 20.06.2017
 */

namespace RadnoK\PayUBundle\Manager;

use RadnoK\PayUBundle\Model\OrderInterface;

abstract class OrderManager implements OrderManagerInterface
{
    /**
     * @return OrderInterface
     */
    public function createOrder()
    {
        $class = $this->getClass();

        return new $class;
    }

    /**
     * @param $orderId
     * @return mixed
     */
    public function findOrderByOrderId($orderId)
    {
        return $this->findOrderBy(['orderId' => $orderId]);
    }
}
