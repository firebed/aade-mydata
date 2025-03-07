<?php

namespace Firebed\AadeMyData\Services;

use Firebed\AadeMyData\Enums\TaxType;
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Enums\StampCategory;
use Firebed\AadeMyData\Enums\FeesPercentCategory;
use Firebed\AadeMyData\Enums\WithheldPercentCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Enums\ExpenseClassificationType;
use Firebed\AadeMyData\Enums\OtherTaxesPercentCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\ExpenseClassificationCategory;

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

    public static function expenseClassificationExists(InvoiceType|string $invoiceType, ExpenseClassificationCategory|string $category, ExpenseClassificationType|string|null $type = null): bool
    {
        $classifications = self::expenseClassifications($invoiceType);
        return $classifications->contains($category) && $classifications->get($category)->contains($type);
    }

    /**
     * Checks if a given tax classification exists for a specific tax type and tax category.
     *
     * This function validates whether a tax category is valid for a given tax type. Each tax type
     * corresponds to a specific set of tax categories, represented by enums. If the tax category
     * is valid for the tax type, the function returns `true`; otherwise, it returns `false`.
     *
     * @param TaxType|int $taxType The tax type to check. Can be an instance of `TaxType` or an integer.
     * @param int|null $taxCategory The tax category to validate. Can be an integer or `null` (for tax types that don't require a category).
     * @return bool Returns `true` if the tax classification exists for the given tax type and category, otherwise `false`.
     *
     * @example
     * Check if tax category 1 exists for TaxType::TYPE_1
     * taxClassificationExists(TaxType::TYPE_1, 1); // Returns true if valid, false otherwise
     *
     * Check if tax category is valid for TaxType::TYPE_5 (which requires no category)
     * taxClassificationExists(TaxType::TYPE_5, null); // Returns true
     */
    public static function taxClassificationExists(TaxType|int $taxType, ?int $taxCategory): bool
    {
        if (!$taxType instanceof TaxType) {
            $taxType = TaxType::tryFrom($taxType);
            if ($taxType === null) {
                return false; // Handle unexpected TaxType values
            }
        }

        return match ($taxType) {
            TaxType::TYPE_1 => WithheldPercentCategory::tryFrom($taxCategory) !== null, // Παρακρατούμενοι Φόροι
            TaxType::TYPE_2 => FeesPercentCategory::tryFrom($taxCategory) !== null, // Τέλη
            TaxType::TYPE_3 => OtherTaxesPercentCategory::tryFrom($taxCategory) !== null, // Άλλοι Φόροι
            TaxType::TYPE_4 => StampCategory::tryFrom($taxCategory) !== null, // Χαρτόσημο
            TaxType::TYPE_5 => $taxCategory === null, // Κρατήσεις
        };
    }
}
