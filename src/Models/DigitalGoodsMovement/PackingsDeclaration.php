<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models\DigitalGoodsMovement;

use Firebed\AadeMyData\Casts\Arr;
use Firebed\AadeMyData\Models\ModelCollection;

/**
 * Δήλωση Συσκευασιών: the packages of an invoice, each one written as a
 * <code>Packages</code> element inside <code>packingsDeclarations</code>.
 *
 * @extends ModelCollection<PackagingDetail>
 *
 * @version 2.0.1
 */
class PackingsDeclaration extends ModelCollection
{
    protected function casts(): array
    {
        return [
            'Packages' => Arr::of(PackagingDetail::class),
        ];
    }
}
