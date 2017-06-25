<?php
/**
 * Created by radnok <alfaro.konrad@gmail.com>.
 * Date: 23.06.2017
 */

namespace RadnoK\PayUBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class OrderStatusType extends AbstractEnumType
{
    const COMPLETED = 'COMPLETED';
    const CANCELED = 'CANCELED';
    const PENDING = 'PENDING';

    protected static $choices = [
        self::COMPLETED => 'radnok.payments.types.completed',
        self::CANCELED  => 'radnok.payments.types.canceled',
        self::PENDING   => 'radnok.payments.types.pending',
    ];
}
