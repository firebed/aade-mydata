<?php

namespace Firebed\AadeMyData\Services;

use ArrayAccess;
use ArrayIterator;
use BackedEnum;
use Firebed\AadeMyData\Enums\ExpenseClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use IteratorAggregate;
use Traversable;

class CategoryClassificationCollection implements ArrayAccess, IteratorAggregate
{
    private array $classifications;
    private bool  $isIncome;

    public function __construct(array $classifications, bool $isIncome)
    {
        $this->classifications = $classifications;
        $this->isIncome = $isIncome;
    }

    public function toKeyLabel(): array
    {
        $results = [];

        foreach ($this->keys() as $categoryKey) {
            $enum = $this->toEnum($categoryKey);
            $results[$categoryKey] = $enum->label();
        }

        return $results;
    }

    public function toKeyLabels(): array
    {
        $results = [];

        foreach ($this->keys() as $categoryKey) {
            $types = $this->get($categoryKey);
            $results[$categoryKey] = $types->toKeyLabel();
        }

        return $results;
    }

    public function keys(): array
    {
        return array_keys($this->classifications);
    }

    public function uniqueTypes(): TypeClassificationCollection
    {
        return new TypeClassificationCollection(
            array_unique(array_merge(...array_values($this->classifications))),
            $this->isIncome
        );
    }

    public function toArray(): array
    {
        return $this->classifications;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->classifications[$offset]);
    }

    public function contains(mixed $value): bool
    {
        if ($value instanceof BackedEnum) {
            $value = $value->value;
        }

        return $this->offsetExists($value);
    }

    public function offsetGet(mixed $offset): TypeClassificationCollection
    {
        return new TypeClassificationCollection($this->classifications[$offset] ?? [], $this->isIncome);
    }

    public function get(mixed $offset): TypeClassificationCollection
    {
        if ($offset instanceof BackedEnum) {
            return $this->offsetGet($offset->value);
        }

        return $this->offsetGet($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->classifications[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->classifications[$offset]);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->classifications);
    }

    private function toEnum(mixed $value): IncomeClassificationCategory|ExpenseClassificationCategory|null
    {
        if (is_string($value)) {
            return $this->isIncome
                ? IncomeClassificationCategory::tryFrom($value)
                : ExpenseClassificationCategory::tryFrom($value);
        }

        return $value;
    }
}