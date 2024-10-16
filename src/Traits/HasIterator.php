<?php

namespace Firebed\AadeMyData\Traits;

use ArrayIterator;
use Traversable;

/**
 * Trait HasIterator
 * 
 * @template Model
 * @implements \ArrayAccess<int|string, Model>
 * @implements \IteratorAggregate<int|string, Model>
 */
trait HasIterator
{
    /**
     * Returns an iterator for the attributes.
     *
     * @return Traversable<int|string, Model>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->attributes);
    }
    
    /**
     * Returns the count of attributes.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->attributes);
    }
    
    /**
     * Checks if the given offset exists in the attributes.
     *
     * @param int|string $offset
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->attributes);
    }

    /**
     * Gets the value at the given offset.
     *
     * @param int|string $offset
     * @return Model|null
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->get($offset);
    }

    /**
     * Sets the value at the given offset.
     *
     * @param int|string $offset
     * @param Model $value
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->set($offset, $value);
    }

    /**
     * Unsets the value at the given offset.
     *
     * @param int|string $offset
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->attributes[$offset]);
    }