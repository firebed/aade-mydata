<?php

namespace Firebed\AadeMyData\Models;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int, Invoice>
 * @implements ArrayAccess<int, Invoice>
 */
class InvoicesDoc extends Type implements IteratorAggregate, ArrayAccess, Countable
{    
    public array $casts = [
        'invoice' => Invoice::class,
    ];
    
    public function __construct(array $invoices = [])
    {
        $this->attributes['invoice'] = $invoices;
    }
    
    public function addInvoice(Invoice $invoice): void
    {
        $this->attributes['invoice'][] = $invoice;
    }

    public function push($key, $value = null): void
    {
        $this->addInvoice($value);
    }

    /**
     * @return Traversable<int, Invoice>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->attributes['invoice']);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->attributes['invoice'][$offset]);
    }

    public function offsetGet(mixed $offset): Invoice
    {
        return $this->attributes['invoice'][$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->attributes['invoice'][$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->attributes['invoice'][$offset]);
    }

    public function count(): int
    {
        return count($this->attributes['invoice']);
    }
}