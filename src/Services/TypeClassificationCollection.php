<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Services;

use ArrayAccess;
use ArrayIterator;
use BackedEnum;
use Firebed\AadeMyData\Enums\ExpenseClassificationType;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use IteratorAggregate;
use Traversable;

/**
 * @implements ArrayAccess<int|string, IncomeClassificationType|ExpenseClassificationType|string>
 * @implements IteratorAggregate<int|string, IncomeClassificationType|ExpenseClassificationType|string>
 */
class TypeClassificationCollection implements ArrayAccess, IteratorAggregate
{
    /**
     * @var array<int|string, IncomeClassificationType|ExpenseClassificationType|string>
     */
    private array $classifications;

    private bool $isIncome;

    /**
     * @param  array<int|string, IncomeClassificationType|ExpenseClassificationType|string>  $classifications
     */
    public function __construct(array $classifications, bool $isIncome)
    {
        $this->classifications = $classifications;
        $this->isIncome = $isIncome;
    }

    /**
     * Converts classifications to an associative array with keys and labels.
     *
     * @return array<string, string>
     */
    public function toKeyLabel(): array
    {
        $results = [];

        foreach ($this->classifications as $classification) {
            $enum = $this->toEnum($classification);

            $results[$enum->value ?? ''] = $enum?->label() ?? '';
        }

        return $results;
    }

    /**
     * Returns the keys of the classifications array.
     *
     * @return array<int|string, IncomeClassificationType|ExpenseClassificationType|string>
     */
    public function keys(): array
    {
        return $this->classifications;
    }

    /**
     * Returns the classifications as an array.
     *
     * @return array<int|string, IncomeClassificationType|ExpenseClassificationType|string>
     */
    public function toArray(): array
    {
        return $this->classifications;
    }

    /**
     * Checks if an offset exists in the classifications array.
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->classifications[$offset]);
    }

    /**
     * Gets the value at the given offset.
     */
    public function offsetGet(mixed $offset): string|null|IncomeClassificationType|ExpenseClassificationType
    {
        return $this->classifications[$offset] ?? null;
    }

    /**
     * Checks if the classifications array contains a specific value.
     */
    public function contains(mixed $value): bool
    {
        if ($value === null) {
            return empty($this->classifications) || in_array($value, $this->classifications, true);
        }

        if ($value instanceof BackedEnum) {
            $value = $value->value;
        }

        return in_array($value, $this->classifications, true);
    }

    /**
     * Gets the classification as an enum if possible.
     */
    public function get(mixed $offset): IncomeClassificationType|ExpenseClassificationType|null
    {
        return $this->toEnum($offset);
    }

    /**
     * Sets the value at the given offset.
     *
     * @param  IncomeClassificationType|ExpenseClassificationType|string  $value
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->classifications[$offset] = $value;
    }

    /**
     * Unsets the value at the given offset.
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->classifications[$offset]);
    }

    /**
     * Returns an iterator for the classifications array.
     *
     * @return Traversable<int|string, IncomeClassificationType|ExpenseClassificationType|string>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->classifications);
    }

    /**
     * Checks if the classifications array is empty.
     */
    public function isEmpty(): bool
    {
        return empty($this->classifications);
    }

    /**
     * Converts a value to the appropriate enum type if possible.
     */
    private function toEnum(mixed $value): IncomeClassificationType|ExpenseClassificationType|null
    {
        if ($value === null) {
            return null;
        }

        if (! is_string($value)) {
            return $value;
        }

        return $this->isIncome
            ? IncomeClassificationType::tryFrom($value)
            : ExpenseClassificationType::tryFrom($value);
    }
}
