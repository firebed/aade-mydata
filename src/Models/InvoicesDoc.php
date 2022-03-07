<?php

namespace Firebed\AadeMyData\Models;

use Countable;
use Firebed\AadeMyData\Traits\HasIterator;
use IteratorAggregate;

class InvoicesDoc extends Type implements IteratorAggregate, Countable
{
    use HasIterator;

    public function addInvoice(Invoice $invoice): void
    {
        $this->attributes[] = $invoice;
    }
}