<?php

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Traits\HasFactory;

/**
 * This class is used to store payment methods and is part of
 * <code>Invoice</code> class.
 * 
 * @extends TypeArray<PaymentMethodDetail>
 */
class PaymentMethods extends TypeArray
{
    use HasFactory;

    protected array $casts = [
        'paymentMethodDetails' => PaymentMethodDetail::class,
    ];

    /**
     * @param PaymentMethodDetail|PaymentMethodDetail[] $paymentMethods
     */
    public function __construct(PaymentMethodDetail|array $paymentMethods = [])
    {
        parent::__construct('paymentMethodDetails', $paymentMethods);
    }

    public function offsetGet(mixed $offset): PaymentMethodDetail
    {
        return $this->attributes['paymentMethodDetails'][$offset];
    }
}