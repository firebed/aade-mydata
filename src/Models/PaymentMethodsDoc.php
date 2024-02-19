<?php

namespace Firebed\AadeMyData\Models;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int, PaymentMethod>
 * @implements ArrayAccess<int, PaymentMethod>
 */
class PaymentMethodsDoc extends Type implements IteratorAggregate, ArrayAccess, Countable
{
    public array $casts = [
        'paymentMethods' => PaymentMethod::class,
    ];

    public function push($key, $value = null): void
    {
        $this->attributes['paymentMethods'][] = $value;
    }

    /**
     * @return Traversable<int, PaymentMethod>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->attributes['paymentMethods']);
    }

    public function offsetExists($offset): bool
    {
        return isset($this->attributes['paymentMethods'][$offset]);
    }

    public function offsetGet($offset): PaymentMethod
    {
        return $this->attributes['paymentMethods'][$offset];
    }

    public function offsetSet($offset, $value): void
    {
        $this->attributes['paymentMethods'][$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->attributes['paymentMethods'][$offset]);
    }

    public function count(): int
    {
        return count($this->attributes['paymentMethods']);
    }
}