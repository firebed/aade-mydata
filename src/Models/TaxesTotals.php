<?php

namespace Firebed\AadeMyData\Models;

use Countable;
use Firebed\AadeMyData\Traits\HasIterator;
use IteratorAggregate;

class TaxesTotals extends Type implements IteratorAggregate, Countable
{
    use HasIterator;

    public function addTaxes(TaxTotals $taxes): void
    {
        $this->attributes[] = $taxes;
    }
}