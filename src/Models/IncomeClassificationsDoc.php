<?php

namespace Firebed\AadeMyData\Models;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use Countable;
use Traversable;

/**
 * @implements IteratorAggregate<int, IncomeClassification>
 * @implements ArrayAccess<int, IncomeClassification>
 */
class IncomeClassificationsDoc extends Type implements IteratorAggregate, ArrayAccess, Countable
{
    public array $casts = [
        'incomeClassification' => IncomeClassification::class,
    ];
    
    public function push($key, $value = null): void
    {
        $this->attributes['incomeClassification'][] = $value;
    }

    /**
     * @return Traversable<int, IncomeClassification>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->attributes['incomeClassification']);
    }

    public function offsetExists($offset): bool
    {
        return isset($this->attributes['incomeClassification'][$offset]);
    }

    public function offsetGet($offset): IncomeClassification
    {
        return $this->attributes['incomeClassification'][$offset];
    }

    public function offsetSet($offset, $value): void
    {
        $this->attributes['incomeClassification'][$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->attributes['incomeClassification'][$offset]);
    }

    public function count(): int
    {
        return count($this->attributes['incomeClassification']);
    }
}