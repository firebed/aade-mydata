<?php

namespace Firebed\AadeMyData\Models;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int, Error>
 * @implements ArrayAccess<int, Error>
 */
class Errors extends Type implements IteratorAggregate, ArrayAccess, \Countable
{
    public array $casts = [
        'error' => Error::class,
    ];
    
    /**
     * @return Traversable<int, Error>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->attributes['error'] ?? []);
    }

    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->attributes['error']);
    }

    public function offsetGet(mixed $offset): Error
    {
        return $this->attributes['error'][$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->attributes['error'][$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->attributes['error'][$offset]);
    }

    public function count(): int
    {
        return count($this->attributes['error']);
    }
}