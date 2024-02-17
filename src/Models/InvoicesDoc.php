<?php

namespace Firebed\AadeMyData\Models;

use Countable;
use Firebed\AadeMyData\Traits\HasIterator;
use IteratorAggregate;

class InvoicesDoc extends Type implements IteratorAggregate, Countable
{
    use HasIterator;
    
    public function __construct(array $invoices = [])
    {
        $this->attributes = $invoices;
    }
    
    public function addInvoice(Invoice $invoice): void
    {
        $this->attributes[] = $invoice;
    }

    /**
     * @return Invoice
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->get($offset);
    }
    
    /**
     * @return Invoice[]
     */
    public function all(): array
    {
        return $this->attributes();
    }
}