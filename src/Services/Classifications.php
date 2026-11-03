<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Services;

use Firebed\AadeMyData\Enums\ExpenseClassificationCategory;
use Firebed\AadeMyData\Enums\ExpenseClassificationType;
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Enums\InvoiceType;

class Classifications
{
    /** @var array<string, array<string, list<string>>> Invoice type => category => types */
    private static array $incomeClassifications;

    /** @var array<string, array<string, list<string>>> Invoice type => category => types */
    private static array $expenseClassifications;

    private static function loadIncomeClassifications(): void
    {
        if (! isset(self::$incomeClassifications)) {
            self::$incomeClassifications = require __DIR__.'/../../config/income-classifications.php';
        }
    }

    private static function loadExpenseClassifications(): void
    {
        if (! isset(self::$expenseClassifications)) {
            self::$expenseClassifications = require __DIR__.'/../../config/expense-classifications.php';
        }
    }

    public static function incomeClassifications(InvoiceType|string $invoiceType, IncomeClassificationCategory|string|null $classificationCategory = null): CategoryClassificationCollection|TypeClassificationCollection
    {
        $categories = self::incomeCategories($invoiceType);

        return $classificationCategory === null ? $categories : $categories->get($classificationCategory);
    }

    private static function incomeCategories(InvoiceType|string $invoiceType): CategoryClassificationCollection
    {
        self::loadIncomeClassifications();

        $key = $invoiceType instanceof InvoiceType ? $invoiceType->value : $invoiceType;

        return new CategoryClassificationCollection(self::$incomeClassifications[$key] ?? [], true);
    }

    public static function expenseClassifications(InvoiceType|string $invoiceType, ExpenseClassificationCategory|string|null $classificationCategory = null): CategoryClassificationCollection|TypeClassificationCollection
    {
        $categories = self::expenseCategories($invoiceType);

        return $classificationCategory === null ? $categories : $categories->get($classificationCategory);
    }

    private static function expenseCategories(InvoiceType|string $invoiceType): CategoryClassificationCollection
    {
        self::loadExpenseClassifications();

        $key = $invoiceType instanceof InvoiceType ? $invoiceType->value : $invoiceType;

        return new CategoryClassificationCollection(self::$expenseClassifications[$key] ?? [], false);
    }

    public static function incomeClassificationExists(InvoiceType|string $invoiceType, IncomeClassificationCategory|string $category, IncomeClassificationType|string|null $type = null): bool
    {
        $categories = self::incomeCategories($invoiceType);

        return $categories->contains($category) && $categories->get($category)->contains($type);
    }

    public static function expenseClassificationExists(InvoiceType|string $invoiceType, ExpenseClassificationCategory|string $category, ExpenseClassificationType|string|null $type = null): bool
    {
        $categories = self::expenseCategories($invoiceType);

        return $categories->contains($category) && $categories->get($category)->contains($type);
    }
}
