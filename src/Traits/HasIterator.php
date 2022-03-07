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
}