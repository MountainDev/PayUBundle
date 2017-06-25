<?php

namespace RadnoK\PayUBundle;

final class RadnoKPayUEvents
{
    const NEW_SINGLE = 'radnok.payu.payment.new.single';

    const NEW_RECURRING = 'radnok.payu.payment.new.recurring';

    const CHARGE_CARDS = 'radnok.payu.charge.cards';

    const PAYMENT_COMPLETED = 'radnok.payu.payment.status.completed';

    const PAYMENT_PENDING = 'radnok.payu.payment.status.pending';

    const PAYMENT_CANCELED = 'radnok.payu.payment.status.canceled';
}
