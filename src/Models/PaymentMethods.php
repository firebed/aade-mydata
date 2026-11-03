<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Casts\Arr;
use Firebed\AadeMyData\Traits\HasFactory;

/**
 * This class is used to store payment methods and is part of
 * <code>Invoice</code> class.
 *
 * @extends ModelCollection<PaymentMethodDetail>
 */
class PaymentMethods extends ModelCollection
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'paymentMethodDetails' => Arr::of(PaymentMethodDetail::class),
        ];
    }

    public function getTotalPaymentAmount(): float
    {
        return round(array_reduce($this->all(), fn ($c, PaymentMethodDetail $pm) => $c + $pm->getAmount(), 0), 2);
    }
}
