<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Casts\Arr;
use Firebed\AadeMyData\Traits\HasFactory;

/**
 * This class is used to store taxes totals and is part of Invoice class.
 *
 * @extends ModelCollection<TaxTotals>
 */
class TaxesTotals extends ModelCollection
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'taxes' => Arr::of(TaxTotals::class),
        ];
    }
}
