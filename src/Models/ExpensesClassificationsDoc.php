<?php

namespace Firebed\AadeMyData\Models;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int, ExpensesClassification>
 * @implements ArrayAccess<int, ExpensesClassification>
 */
class ExpensesClassificationsDoc extends Type implements IteratorAggregate, ArrayAccess, Countable
{
    public array $casts = [
        'expensesClassifications' => ExpensesClassification::class,
    ];
    
    public function push($key, $value = null): void
    {
        $this->attributes['expensesClassifications'][] = $value;
    }

    /**
     * @return Traversable<int, ExpensesClassification>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->attributes['expensesClassifications']);
    }

    public function offsetExists($offset): bool
    {
        return isset($this->attributes['expensesClassifications'][$offset]);
    }

    public function offsetGet($offset): ExpensesClassification
    {
        return $this->attributes['expensesClassifications'][$offset];
    }

    public function offsetSet($offset, $value): void
    {
        $this->attributes['expensesClassifications'][$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->attributes['expensesClassifications'][$offset]);
    }

    public function count(): int
    {
        return count($this->attributes['expensesClassifications']);
    }
}