<?php

namespace Firebed\AadeMyData\Services;

use Firebed\AadeMyData\Enums\ExpenseClassificationCategory;
use Firebed\AadeMyData\Enums\ExpenseClassificationType;
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Enums\InvoiceType;

class Classifications
{
    private static array $incomeClassifications;
    private static array $expenseClassifications;

    private static function loadIncomeClassifications(): void
    {
        if (!isset(self::$incomeClassifications)) {
            self::$incomeClassifications = require __DIR__.'/../../config/income-classifications.php';
        }
    }

    private static function loadExpenseClassifications(): void
    {
        if (!isset(self::$expenseClassifications)) {
            self::$expenseClassifications = require __DIR__.'/../../config/expense-classifications.php';
        }
    }

    public static function incomeClassifications(InvoiceType $type, IncomeClassificationCategory $category = null): CategoryClassificationCollection|TypeClassificationCollection
    {
        self::loadIncomeClassifications();

        $classifications = self::$incomeClassifications[$type->value] ?? [];

        if ($category !== null) {
            return new TypeClassificationCollection($classifications[$category->value] ?? [], true);
        }

        return new CategoryClassificationCollection($classifications, true);
    }

    public static function expenseClassifications(InvoiceType $type, ExpenseClassificationCategory $category = null): CategoryClassificationCollection|TypeClassificationCollection
    {
        self::loadExpenseClassifications();

        $classifications = self::$expenseClassifications[$type->value] ?? [];

        if ($category !== null) {
            return new TypeClassificationCollection($classifications[$category->value] ?? null, false);
        }

        return new CategoryClassificationCollection($classifications, false);
    }

    public static function exists(InvoiceType|string $invoiceType, IncomeClassificationCategory|ExpenseClassificationCategory|string $category, IncomeClassificationType|ExpenseClassificationType|string $type = null): bool
    {
        $invoiceType = is_string($invoiceType) ? InvoiceType::from($invoiceType) : $invoiceType;
        $category = self::resolveCategory($category);
        $type = self::resolveType($type);

        if ($type === null) {
            if ($category instanceof IncomeClassificationCategory) {
                return self::incomeClassifications($invoiceType)->contains($category);
            }

            return self::expenseClassifications($invoiceType)->contains($category);
        }

        if ($category instanceof IncomeClassificationCategory) {
            return self::incomeClassifications($invoiceType, $category)->contains($type);
        }

        return self::expenseClassifications($invoiceType, $category)->contains($type);
    }

    private static function resolveCategory(string|IncomeClassificationCategory|ExpenseClassificationCategory $category): IncomeClassificationCategory|ExpenseClassificationCategory
    {
        if (!is_string($category)) {
            return $category;
        }

        return IncomeClassificationCategory::tryFrom($category) ?? ExpenseClassificationCategory::from($category);
    }

    private static function resolveType(string|IncomeClassificationType|ExpenseClassificationType|null $type): IncomeClassificationType|ExpenseClassificationType|null
    {
        if (!is_string($type)) {
            return $type;
        }

        return IncomeClassificationType::tryFrom($type) ?? ExpenseClassificationType::tryFrom($type);
    }
}