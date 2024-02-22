<?php

namespace Firebed\AadeMyData\Models;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/** *
 * @template TType
 *
 * @template-implements IteratorAggregate<int, TType>
 * @template-implements ArrayAccess<int, TType>
 */
class TypeArray extends Type implements IteratorAggregate, ArrayAccess, Countable
{
    protected string $childKey;

    /**
     * @param string $childKey
     * @param TType|TType[] $items
     */
    public function __construct(string $childKey, mixed $items = null)
    {
        $this->childKey = $childKey;
        
        if ($items !== null) {
            $this->set($childKey, $items);
        }
    }

    /**
     * @param int $key
     * @param TType|TType[] $value
     * @return void
     */
    public function set($key, $value): void
    {
        $value = is_array($value) ? $value : [$value];
        $this->attributes[$this->childKey] = $value;
    }

    /**
     * @param TType $value
     * @return void
     */
    public function add($value): void
    {
        $this->push($this->childKey, $value);
    }
    
    /**
     * @param int $key
     * @param TType|TType[] $value
     * @return void
     */
    public function push($key, $value = null): void
    {
        if (is_array($value)) {
            $this->attributes[$this->childKey] = array_merge($this->attributes[$this->childKey], $value);
        } else {
        $this->attributes[$this->childKey][] = $value;
        }
    }

    /**
     * @return Traversable<int, TType>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->attributes[$this->childKey]);
    }

    /**
     * @param int $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return isset($this->attributes[$this->childKey][$offset]);
    }

    /**
     * @param int $offset
     * @return TType
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->attributes[$this->childKey][$offset];
    }

    /**
     * @param int $offset
     * @param TType $value
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        $this->attributes[$this->childKey][$offset] = $value;
    }

    /**
     * @param int $offset
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->attributes[$this->childKey][$offset]);
    }

    public function count(): int
    {
        return count($this->attributes[$this->childKey]);
    }
}