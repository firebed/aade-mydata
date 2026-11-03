<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Casts\Arr;

/**
 * @extends ModelCollection<CancelledInvoice>
 */
class CancelledInvoicesDoc extends ModelCollection
{
    protected function casts(): array
    {
        return [
            'cancelledInvoice' => Arr::of(CancelledInvoice::class),
        ];
    }
}
