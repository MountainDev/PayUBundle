<?php

namespace RadnoK\PayUBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class PlanType extends AbstractEnumType
{
    const SINGLE = 'single';
    const RECURRING = 'recurring';

    protected static $choices = [
        self::SINGLE    => 'Jednorazowa',
        self::RECURRING => 'Cykliczna',
    ];
}
