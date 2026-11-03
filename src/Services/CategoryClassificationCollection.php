<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Services;

use ArrayAccess;
use ArrayIterator;
use BackedEnum;
use Firebed\AadeMyData\Enums\ExpenseClassificationCategory;
use Firebed\AadeMyData\Enums\ExpenseClassificationType;
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use IteratorAggregate;
use Traversable;

/**
 * The classification categories of an invoice type, each with its types; reading
 * a category gives its types as a TypeClassificationCollection.
 *
 * @phpstan-type Types array<int|string, IncomeClassificationType|ExpenseClassificationType|string>
 *
 * @implements ArrayAccess<string, TypeClassificationCollection|Types>
 * @implements IteratorAggregate<string, Types>
 */
class CategoryClassificationCollection implements ArrayAccess, IteratorAggregate
{
    /**
     * @var array<string, Types>
     */
    private array $classifications;

    private bool $isIncome;

    /**
     * @param  array<string, Types>  $classifications  Category => types
     */
    public function __construct(array $classifications, bool $isIncome)
    {
        $this->classifications = $classifications;
        $this->isIncome = $isIncome;
    }

    /**
     * Converts the categories to an associative array with keys and labels.
     *
     * @return array<string, string>
     */
    public function toKeyLabel(): array
    {
        $results = [];

        foreach ($this->keys() as $categoryKey) {
            $enum = $this->toEnum($categoryKey);
            $results[$categoryKey] = $enum->label();
        }

        return $results;
    }

    /**
     * Converts the categories to an associative array of the key/label arrays of their types.
     *
     * @return array<string, array<string, string>>
     */
    public function toKeyLabels(): array
    {
        $results = [];

        foreach ($this->keys() as $categoryKey) {
            $types = $this->get($categoryKey);
            $results[$categoryKey] = $types->toKeyLabel();
        }

        return $results;
    }

    /**
     * Returns the category keys.
     *
     * @return list<string>
     */
    public function keys(): array
    {
        return array_keys($this->classifications);
    }

    public function uniqueTypes(): TypeClassificationCollection
    {
        return new TypeClassificationCollection(
            array_unique(array_map(
                static fn (IncomeClassificationType|ExpenseClassificationType|string $type) => $type instanceof BackedEnum ? $type->value : $type,
                array_merge(...array_values($this->classifications))
            )),
            $this->isIncome
        );
    }

    /**
     * Returns the classifications as an array.
     *
     * @return array<string, Types>
     */
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

    public function get(IncomeClassificationCategory|ExpenseClassificationCategory|string $offset): TypeClassificationCollection
    {
        return $this->offsetGet($offset instanceof BackedEnum ? $offset->value : $offset);
    }

    /**
     * @param  TypeClassificationCollection|Types  $value  The types of the category
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->classifications[$offset] = $value instanceof TypeClassificationCollection ? $value->toArray() : $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->classifications[$offset]);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->classifications);
    }

    public function isEmpty(): bool
    {
        return empty($this->classifications);
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
