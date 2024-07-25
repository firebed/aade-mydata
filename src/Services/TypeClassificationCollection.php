<?php

namespace Firebed\AadeMyData\Services;

use ArrayAccess;
use ArrayIterator;
use BackedEnum;
use Firebed\AadeMyData\Enums\ExpenseClassificationType;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use IteratorAggregate;
use Traversable;

class TypeClassificationCollection implements ArrayAccess, IteratorAggregate
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

        foreach ($this->classifications as $classification) {
            $enum = $this->toEnum($classification);

            $results[$enum?->value ?? ""] = $enum?->label() ?? "";
        }

        return $results;
    }

    public function keys(): array
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        return $this->classifications;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->classifications[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->classifications[$offset];
    }

    public function contains(mixed $value): bool
    {
        if ($value instanceof BackedEnum) {
            $value = $value->value;
        }
        
        return in_array($value, $this->toArray(), true);
    }

    public function get(mixed $offset): IncomeClassificationType|ExpenseClassificationType|null
    {
        return $this->toEnum($offset);
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

    private function toEnum(mixed $value): IncomeClassificationType|ExpenseClassificationType|null
    {
        if ($value === null) {
            return null;
        }

        if (!is_string($value)) {
            return $value;
        }

        return $this->isIncome
            ? IncomeClassificationType::tryFrom($value)
            : ExpenseClassificationType::tryFrom($value);
    }
}