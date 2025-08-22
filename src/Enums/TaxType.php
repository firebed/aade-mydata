<?php

namespace Firebed\AadeMyData\Enums;

use BackedEnum;

enum TaxType: int
{
    use HasLabels;

    /**
     * Παρακρατούμενος Φόρος
     */
    case TYPE_1 = 1;

    /**
     * Τέλη
     */
    case TYPE_2 = 2;

    /**
     * Λοιποί Φόροι
     */
    case TYPE_3 = 3;

    /**
     * Ψηφιακό Τέλος Συναλλαγής
     */
    case TYPE_4 = 4;

    /**
     * Κρατήσεις
     */
    case TYPE_5 = 5;

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
     * Check if tax category 1 exists for TaxType::TYPE_1
     * taxClassificationExists(TaxType::TYPE_1, 1); // Returns true if valid, false otherwise
     * taxClassificationExists(TaxType::TYPE_1, WithheldPercentCategory::TAX_1); // Returns true if valid, false otherwise
     *
     * Check if tax category is valid for TaxType::TYPE_5 (which requires no category)
     * taxClassificationExists(TaxType::TYPE_5, null); // Returns true
     */
    public static function taxClassificationExists(TaxType|int $taxType, BackedEnum|int|null $taxCategory): bool
    {
        if (!$taxType instanceof TaxType) {
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
            return $this === self::TYPE_5;
        }

        if ($taxCategory instanceof BackedEnum) {
            return match ($this) {
                self::TYPE_1 => $taxCategory instanceof WithheldPercentCategory, // Παρακρατούμενοι Φόροι
                self::TYPE_2 => $taxCategory instanceof FeesPercentCategory, // Τέλη
                self::TYPE_3 => $taxCategory instanceof OtherTaxesPercentCategory, // Άλλοι Φόροι
                self::TYPE_4 => $taxCategory instanceof StampCategory, // Ψηφιακό Τέλος Συναλλαγής
                default => false,
            };
        }

        return match ($this) {
            self::TYPE_1 => WithheldPercentCategory::tryFrom($taxCategory) !== null, // Παρακρατούμενοι Φόροι
            self::TYPE_2 => FeesPercentCategory::tryFrom($taxCategory) !== null, // Τέλη
            self::TYPE_3 => OtherTaxesPercentCategory::tryFrom($taxCategory) !== null, // Άλλοι Φόροι
            self::TYPE_4 => StampCategory::tryFrom($taxCategory) !== null, // Ψηφιακό Τέλος Συναλλαγής
            default => false,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::TYPE_1 => "Παρακρατούμενος Φόρος",
            self::TYPE_2 => "Τέλη",
            self::TYPE_3 => "Λοιποί Φόροι",
            self::TYPE_4 => "Ψηφιακό Τέλος Συναλλαγής",
            self::TYPE_5 => "Κρατήσεις",
        };
    }
}