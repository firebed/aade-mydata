<?php

namespace Firebed\AadeMyData\Models;

/**
 * This class is used to store payment methods and is part of
 * <code>RequestedDoc</code> class.
 * 
 * @extends TypeArray<PaymentMethod>
 */
class PaymentMethodsDoc extends TypeArray
{
    protected array $casts = [
        'paymentMethods' => PaymentMethod::class,
    ];

    public function __construct()
    {
        parent::__construct('paymentMethods');
    }

    public function offsetGet($offset): PaymentMethod
    {
        return $this->attributes['paymentMethods'][$offset];
    }
}