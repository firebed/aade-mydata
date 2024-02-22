<?php

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Traits\HasFactory;

/**
 * This class is used to store taxes totals and is part of Invoice class.
 * 
 * @extends TypeArray<TaxTotals>
 */
class TaxesTotals extends TypeArray
{
    use HasFactory;

    protected array $casts = [
        'taxes' => TaxTotals::class,
    ];

    /**
     * @param TaxTotals|TaxTotals[] $taxes
     */
    public function __construct(array $taxes = [])
    {
        parent::__construct('taxes', $taxes);
    }

    public function offsetGet(mixed $offset): TaxTotals
    {
        return $this->attributes['taxes'][$offset];
    }
}