<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Enums;

use BackedEnum;

enum TaxType: int
{
    use HasLabels;

    /**
     * Παρακρατούμενος Φόρος
     */
    #[Label('Παρακρατούμενος Φόρος')]
    case WITHHELD_TAX = 1;

    /**
     * Τέλη
     */
    #[Label('Τέλη')]
    case FEES = 2;

    /**
     * Λοιποί Φόροι
     */
    #[Label('Λοιποί Φόροι')]
    case OTHER_TAXES = 3;

    /**
     * Ψηφιακό Τέλος Συναλλαγής
     */
    #[Label('Ψηφιακό Τέλος Συναλλαγής')]
    case STAMP_DUTY = 4;

    /**
     * Κρατήσεις
     */
    #[Label('Κρατήσεις')]
    case DEDUCTIONS = 5;

    /**
     * Checks if a given tax category exists for a specific tax type.
     *
     * This function validates whether a tax category is valid for a given tax type. Each tax type
     * corresponds to a specific set of tax categories, represented by enums. If the tax category
     * is valid for the tax type, the function returns `true`; otherwise, it returns `false`.
     *
     * @param  TaxType|int  $taxType  The tax type to check. Can be an instance of `TaxType` or an integer.
     * @param  BackedEnum|int|null  $taxCategory  The tax category to validate. Can be a tax category enum, an integer or `null` (for tax types that don't require a category).
     * @return bool Returns `true` if the tax category exists for the given tax type, otherwise `false`.
     *
     * @example
     * Check if tax category 1 exists for TaxType::WITHHELD_TAX
     * taxClassificationExists(TaxType::WITHHELD_TAX, 1); // Returns true if valid, false otherwise
     * taxClassificationExists(TaxType::WITHHELD_TAX, WithheldPercentCategory::INTEREST_15); // Returns true if valid, false otherwise
     *
     * Check if tax category is valid for TaxType::DEDUCTIONS (which requires no category)
     * taxClassificationExists(TaxType::DEDUCTIONS, null); // Returns true
     */
    public static function taxClassificationExists(TaxType|int $taxType, BackedEnum|int|null $taxCategory): bool
    {
        if (! $taxType instanceof TaxType) {
            $taxType = TaxType::tryFrom($taxType);
            if ($taxType === null) {
                return false; // Handle unexpected TaxType values
            }
        }

        return $taxType->supportsTaxCategory($taxCategory);
    }

    /**
     * Determines whether a given tax category is supported by the current tax type.
     *
     * @param  BackedEnum|int|null  $taxCategory  The tax category to check. Can be an instance of a backed enum, an integer, or null.
     * @return bool Returns `true` if the tax category is supported for the current tax type, otherwise `false`.
     */
    public function supportsTaxCategory(BackedEnum|int|null $taxCategory): bool
    {
        // Check for null tax category to avoid tryFrom calls on null values
        if ($taxCategory === null) {
            return $this === self::DEDUCTIONS;
        }

        if ($taxCategory instanceof BackedEnum) {
            return match ($this) {
                self::WITHHELD_TAX => $taxCategory instanceof WithheldPercentCategory, // Παρακρατούμενοι Φόροι
                self::FEES => $taxCategory instanceof FeesPercentCategory, // Τέλη
                self::OTHER_TAXES => $taxCategory instanceof OtherTaxesPercentCategory, // Άλλοι Φόροι
                self::STAMP_DUTY => $taxCategory instanceof StampCategory, // Ψηφιακό Τέλος Συναλλαγής
                default => false,
            };
        }

        return match ($this) {
            self::WITHHELD_TAX => WithheldPercentCategory::tryFrom($taxCategory) !== null, // Παρακρατούμενοι Φόροι
            self::FEES => FeesPercentCategory::tryFrom($taxCategory) !== null, // Τέλη
            self::OTHER_TAXES => OtherTaxesPercentCategory::tryFrom($taxCategory) !== null, // Άλλοι Φόροι
            self::STAMP_DUTY => StampCategory::tryFrom($taxCategory) !== null, // Ψηφιακό Τέλος Συναλλαγής
            default => false,
        };
    }
}
