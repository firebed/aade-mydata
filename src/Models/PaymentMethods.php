<?php

namespace Firebed\AadeMyData\Models;

class PaymentMethods extends Type
{
    public function addPaymentMethod(PaymentMethodDetailType $paymentMethod): self
    {
        return $this->put('', $paymentMethod);
    }

    public function put($key, $value): self
    {
        $this->attributes['paymentMethodDetails'][] = $value;
        return $this;
    }
}