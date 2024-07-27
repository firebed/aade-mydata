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

    public static function incomeClassifications(InvoiceType|string $invoiceType, IncomeClassificationCategory|string $classificationCategory = null): CategoryClassificationCollection|TypeClassificationCollection
    {
        self::loadIncomeClassifications();

        if ($invoiceType instanceof InvoiceType) {
            $invoiceType = $invoiceType->value;
        }

        $classifications = self::$incomeClassifications[$invoiceType] ?? [];

        if ($classificationCategory === null) {
            return new CategoryClassificationCollection($classifications, true);
        }

        if ($classificationCategory instanceof IncomeClassificationCategory) {
            $classificationCategory = $classificationCategory->value;
        }

        return new TypeClassificationCollection($classifications[$classificationCategory] ?? [], true);
    }

    public static function expenseClassifications(InvoiceType|string $invoiceType, ExpenseClassificationCategory|string $classificationCategory = null): CategoryClassificationCollection|TypeClassificationCollection
    {
        self::loadExpenseClassifications();

        if ($invoiceType instanceof InvoiceType) {
            $invoiceType = $invoiceType->value;
        }

        $classifications = self::$expenseClassifications[$invoiceType] ?? [];

        if ($classificationCategory === null) {
            return new CategoryClassificationCollection($classifications, false);
        }

        if ($classificationCategory instanceof ExpenseClassificationCategory) {
            $classificationCategory = $classificationCategory->value;
        }

        return new TypeClassificationCollection($classifications[$classificationCategory] ?? [], false);
    }

    public static function incomeClassificationExists(InvoiceType|string $invoiceType, IncomeClassificationCategory|string $category, IncomeClassificationType|string|null $type = null): bool
    {
        $classifications = self::incomeClassifications($invoiceType);

        return $classifications->contains($category) && $classifications->get($category)->contains($type);
    }

    public static function expenseClassificationExists(InvoiceType|string $invoiceType, ExpenseClassificationCategory|string|null $category, ExpenseClassificationType|string|null $type = null): bool
    {
        if ($category === null && $type === null) {
            return false;
        }

        if ($category !== null) {
            $classifications = self::expenseClassifications($invoiceType);
            return $classifications->contains($category) && $classifications->get($category)->contains($type);
        }

        if (is_string($type)) {
            $type = ExpenseClassificationType::tryFrom($type);
        }
        
        return $type->isVatClassification();
    }
}
