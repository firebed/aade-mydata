<?php

namespace Firebed\AadeMyData\Traits;

use ArrayIterator;
use Traversable;

trait HasIterator
{
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->attributes);
    }
    
    public function count(): int
    {
        return count($this->attributes);
    }
    
    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->attributes);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->get($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->set($offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->attributes[$offset]);
    }
}