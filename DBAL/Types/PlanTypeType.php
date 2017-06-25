<?php
/**
 * Created by radnok <alfaro.konrad@gmail.com>
 * Date: 19.06.2017
 */

namespace RadnoK\PayUBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class PlanTypeType extends AbstractEnumType
{
    const SINGLE = 'SINGLE';
    const RECURRING = 'RECURRING';
    const CARD = 'CARD';

    protected static $choices = [
        self::SINGLE    => 'radnok.payments.types.single',
        self::RECURRING => 'radnok.payments.types.recurring',
        self::CARD      => 'radnok.payments.types.card',
    ];
}
