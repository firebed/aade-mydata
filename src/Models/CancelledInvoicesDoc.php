<?php

namespace Firebed\AadeMyData\Models;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int, CancelledInvoice>
 * @implements ArrayAccess<int, CancelledInvoice>
 */
class CancelledInvoicesDoc extends Type implements IteratorAggregate, ArrayAccess, Countable
{
    public array $casts = [
        'cancelledInvoice' => CancelledInvoice::class,
    ];

    public function addCancelledInvoice(CancelledInvoice $cancelledInvoice): void
    {
        $this->attributes['cancelledInvoice'][] = $cancelledInvoice;
    }

    public function push($key, $value = null): void
    {
        $this->addCancelledInvoice($value);
    }

    /**
     * @return Traversable<int, CancelledInvoice>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->attributes['cancelledInvoice']);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->attributes['cancelledInvoice'][$offset]);
    }

    public function offsetGet(mixed $offset): CancelledInvoice
    {
        return $this->attributes['cancelledInvoice'][$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->attributes['cancelledInvoice'][$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->attributes['cancelledInvoice'][$offset]);
    }

    public function count(): int
    {
        return count($this->attributes['cancelledInvoice']);
    }
}