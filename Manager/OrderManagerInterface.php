<?php
/**
 * Created by radnok <alfaro.konrad@gmail.com>.
 * Date: 20.06.2017
 */

namespace RadnoK\PayUBundle\Manager;

use RadnoK\PayUBundle\Model\OrderInterface;

interface OrderManagerInterface
{
    public function create(): OrderInterface;

    public function update(OrderInterface $order);

    public function getClass();

    public function findOrderBy($criteria);
}