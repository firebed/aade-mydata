<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models;

use ArrayAccess;
use ArrayIterator;
use Countable;
use Firebed\AadeMyData\Casts\Arr;
use IteratorAggregate;
use LogicException;
use Traversable;

/**
 * A myDATA wrapper element that holds a list of same-named children, e.g.
 * <code>paymentMethods</code> with its <code>paymentMethodDetails</code>. The
 * single list attribute declared in casts() names the child element and the
 * type of its items:
 *
 * <pre>
 * protected function casts(): array
 * {
 *     return ['paymentMethodDetails' => Arr::of(PaymentMethodDetail::class)];
 * }
 * </pre>
 *
 * @template TItem
 *
 * @template-implements IteratorAggregate<int, TItem>
 * @template-implements ArrayAccess<int, TItem>
 */
abstract class ModelCollection extends Model implements ArrayAccess, Countable, IteratorAggregate
{
    /** @var array<class-string<static>, string> */
    private static array $childKeys = [];

    /**
     * @param  TItem|list<TItem>|array<string, list<TItem>>|null  $items  The items, one item, or [childKey => items] as toArray() gives it
     */
    public function __construct(mixed $items = null)
    {
        parent::__construct();

        if ($items !== null && $items !== []) {
            $this->set($this->childKey(), $items);
        }
    }

    /**
     * The name of the repeated child element: the one Arr::of() attribute of casts().
     */
    public function childKey(): string
    {
        return self::$childKeys[static::class] ??= $this->resolveChildKey();
    }

    private function resolveChildKey(): string
    {
        $lists = array_keys(array_filter($this->casts(), fn ($cast) => $cast instanceof Arr));

        if (count($lists) !== 1) {
            throw new LogicException(static::class.' must declare exactly one Arr::of() attribute in casts().');
        }

        return $lists[0];
    }

    /**
     * Sets the list: a list replaces the items, a single value is appended. The key is
     * ignored: a collection only has its child list, so <code>$collection->set($anything, $items)</code>
     * and the attribute array form <code>[childKey => items]</code> both land on it.
     *
     * @param  TItem|list<TItem>|array<string, list<TItem>>|null  $value
     */
    public function set(string $key, mixed $value): static
    {
        $childKey = $this->childKey();

        // The attribute array form, as toArray() gives it; any other associative array is one item's attributes
        if (is_array($value) && array_key_exists($childKey, $value)) {
            $value = $value[$childKey];
        }

        if ($value === null) {
            $this->attributes[$childKey] = [];

            return $this;
        }

        return parent::set($childKey, $value);
    }

    /**
     * Appends one item. The key is ignored, as in set().
     *
     * @param  TItem|null  $value
     */
    public function push(string $key, mixed $value): static
    {
        return parent::push($this->childKey(), $value);
    }

    /**
     * @param  TItem  $value
     */
    public function add(mixed $value): static
    {
        return $this->push($this->childKey(), $value);
    }

    /**
     * @return TItem|null
     */
    public function first(): mixed
    {
        return $this->all()[0] ?? null;
    }

    /**
     * @return TItem|null
     */
    public function last(): mixed
    {
        $all = $this->all();

        return $all === [] ? null : $all[array_key_last($all)];
    }

    /**
     * @return list<TItem>
     */
    public function all(): array
    {
        return $this->attributes[$this->childKey()] ?? [];
    }

    public function isEmpty(): bool
    {
        return $this->all() === [];
    }

    public function isNotEmpty(): bool
    {
        return ! $this->isEmpty();
    }

    /**
     * @template TMapped
     *
     * @param  callable(TItem): TMapped  $callback
     * @return list<TMapped>
     */
    public function map(callable $callback): array
    {
        return array_map($callback, $this->all());
    }

    /**
     * @param  callable(TItem): bool  $callback
     * @return list<TItem>
     */
    public function filter(callable $callback): array
    {
        return array_values(array_filter($this->all(), $callback));
    }

    /**
     * @return Traversable<int, TItem>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->all());
    }

    public function count(): int
    {
        return count($this->all());
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->attributes[$this->childKey()][$offset]);
    }

    /**
     * @return TItem|null
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->attributes[$this->childKey()][$offset] ?? null;
    }

    /**
     * @param  TItem  $value
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset === null) {
            $this->add($value);

            return;
        }

        $childKey = $this->childKey();
        $value = $this->castValue($childKey, $value, $this->cast($childKey));

        if ($value !== null) {
            $this->attributes[$childKey][$offset] = $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->attributes[$this->childKey()][$offset]);
        $this->attributes[$this->childKey()] = array_values($this->attributes[$this->childKey()] ?? []);
    }
}
