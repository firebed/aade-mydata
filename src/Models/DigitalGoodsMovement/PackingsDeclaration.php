<?php

namespace Firebed\AadeMyData\Models\DigitalGoodsMovement;

use Firebed\AadeMyData\Models\TypeArray;

/**
 * Δήλωση Συσκευασιών: the packages of an invoice, each one written as a
 * <code>Packages</code> element inside <code>packingsDeclarations</code>.
 *
 * @extends TypeArray<PackagingDetail>
 * @version 2.0.1
 */
class PackingsDeclaration extends TypeArray
{
    protected array $casts = [
        'Packages' => PackagingDetail::class,
    ];

    /**
     * @param PackagingDetail|PackagingDetail[] $packages
     */
    public function __construct(PackagingDetail|array $packages = [])
    {
        parent::__construct('Packages', $packages);
    }
}
