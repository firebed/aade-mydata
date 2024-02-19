<?php

namespace Firebed\AadeMyData\Models;

use ArrayAccess;
use ArrayIterator;
use Countable;
use Firebed\AadeMyData\Traits\HasFactory;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int, TaxTotals>
 * @implements ArrayAccess<int, TaxTotals>
 */
class TaxesTotals extends Type implements IteratorAggregate, ArrayAccess, Countable
{
    use HasFactory;
    
    public array $casts = [
        'taxes' => TaxTotals::class,
    ];

    public function __construct(array $taxes = [])
    {
        $this->attributes['taxes'] = $taxes;
    }

    public function addTaxes(TaxTotals $taxes): void
    {
        $this->attributes['taxes'][] = $taxes;
    }

    public function push($key, $value = null): void
    {
        $this->attributes['taxes'][] = $value;
    }

    public function set($key, $value): void
    {
        $value = is_array($value) ? $value : [$value];
        $this->attributes['taxes'] = $value;
    }

    /**
     * @return Traversable<int, TaxTotals>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->attributes['taxes'] ?? []);
    }

    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->attributes['taxes']);
    }

    public function offsetGet(mixed $offset): TaxTotals
    {
        return $this->attributes['taxes'][$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->attributes['taxes'][$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->attributes['taxes'][$offset]);
    }

    public function count(): int
    {
        return count($this->attributes['taxes']);
    }
}