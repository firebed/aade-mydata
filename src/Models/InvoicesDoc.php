<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Casts\Arr;

/**
 * @extends ModelCollection<Invoice>
 */
class InvoicesDoc extends ModelCollection
{
    protected function casts(): array
    {
        return [
            'invoice' => Arr::of(Invoice::class),
        ];
    }
}
