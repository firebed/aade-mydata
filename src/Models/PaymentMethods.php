<?php

namespace Firebed\AadeMyData\Models;

use Countable;
use Firebed\AadeMyData\Traits\HasIterator;
use IteratorAggregate;

class PaymentMethods extends Type implements IteratorAggregate, Countable
{
    use HasIterator;

    public function addPaymentMethod(PaymentMethodDetail $paymentMethod): void
    {
        $this->put('', $paymentMethod);
    }
}