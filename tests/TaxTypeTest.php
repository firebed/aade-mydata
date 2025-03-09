<?php

namespace Tests;

use Firebed\AadeMyData\Enums\FeesPercentCategory;
use Firebed\AadeMyData\Enums\TaxType;
use Firebed\AadeMyData\Enums\WithheldPercentCategory;
use PHPUnit\Framework\TestCase;

class TaxTypeTest extends TestCase
{
    public function test_tax_combination(): void
    {
        $this->assertTrue(TaxType::taxClassificationExists(TaxType::TYPE_1, WithheldPercentCategory::TAX_1->value));
        $this->assertTrue(TaxType::taxClassificationExists(TaxType::TYPE_1, WithheldPercentCategory::TAX_1));
        $this->assertTrue(TaxType::taxClassificationExists(TaxType::TYPE_5, null));
        $this->assertFalse(TaxType::taxClassificationExists(TaxType::TYPE_1, FeesPercentCategory::TYPE_22->value));
        $this->assertFalse(TaxType::taxClassificationExists(TaxType::TYPE_1, FeesPercentCategory::TYPE_22));
        $this->assertFalse(TaxType::taxClassificationExists(TaxType::TYPE_1, null));

        $this->assertTrue(TaxType::TYPE_1->supportsTaxCategory(WithheldPercentCategory::TAX_1->value));
        $this->assertTrue(TaxType::TYPE_1->supportsTaxCategory(WithheldPercentCategory::TAX_1));
        $this->assertTrue(TaxType::TYPE_5->supportsTaxCategory(null));
        $this->assertFalse(TaxType::TYPE_1->supportsTaxCategory(FeesPercentCategory::TYPE_22->value));
        $this->assertFalse(TaxType::TYPE_1->supportsTaxCategory(FeesPercentCategory::TYPE_22));
        $this->assertFalse(TaxType::TYPE_1->supportsTaxCategory(null));

        $this->assertFalse(TaxType::taxClassificationExists(999, 1));
    }
}