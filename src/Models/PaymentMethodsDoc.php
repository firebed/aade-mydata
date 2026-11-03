<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Casts\Arr;

/**
 * This class is used to store payment methods and is part of
 * <code>RequestedDoc</code> class.
 *
 * @extends ModelCollection<PaymentMethod>
 */
class PaymentMethodsDoc extends ModelCollection
{
    protected function casts(): array
    {
        return [
            'paymentMethods' => Arr::of(PaymentMethod::class),
        ];
    }
}
