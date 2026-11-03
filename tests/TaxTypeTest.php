<?php

declare(strict_types=1);

namespace Tests;

use Firebed\AadeMyData\Enums\FeesPercentCategory;
use Firebed\AadeMyData\Enums\TaxType;
use Firebed\AadeMyData\Enums\WithheldPercentCategory;
use PHPUnit\Framework\TestCase;

class TaxTypeTest extends TestCase
{
    public function test_tax_combination(): void
    {
        $this->assertTrue(TaxType::taxClassificationExists(TaxType::WITHHELD_TAX, WithheldPercentCategory::INTEREST_15->value));
        $this->assertTrue(TaxType::taxClassificationExists(TaxType::WITHHELD_TAX, WithheldPercentCategory::INTEREST_15));
        $this->assertTrue(TaxType::taxClassificationExists(TaxType::DEDUCTIONS, null));
        $this->assertFalse(TaxType::taxClassificationExists(TaxType::WITHHELD_TAX, FeesPercentCategory::OTHER_GROSS_REVENUE_FEES->value));
        $this->assertFalse(TaxType::taxClassificationExists(TaxType::WITHHELD_TAX, FeesPercentCategory::OTHER_GROSS_REVENUE_FEES));
        $this->assertFalse(TaxType::taxClassificationExists(TaxType::WITHHELD_TAX, null));

        $this->assertTrue(TaxType::WITHHELD_TAX->supportsTaxCategory(WithheldPercentCategory::INTEREST_15->value));
        $this->assertTrue(TaxType::WITHHELD_TAX->supportsTaxCategory(WithheldPercentCategory::INTEREST_15));
        $this->assertTrue(TaxType::DEDUCTIONS->supportsTaxCategory(null));
        $this->assertFalse(TaxType::WITHHELD_TAX->supportsTaxCategory(FeesPercentCategory::OTHER_GROSS_REVENUE_FEES->value));
        $this->assertFalse(TaxType::WITHHELD_TAX->supportsTaxCategory(FeesPercentCategory::OTHER_GROSS_REVENUE_FEES));
        $this->assertFalse(TaxType::WITHHELD_TAX->supportsTaxCategory(null));

        $this->assertFalse(TaxType::taxClassificationExists(999, 1));
    }
}
