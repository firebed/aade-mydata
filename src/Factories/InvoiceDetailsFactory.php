<?php

namespace Firebed\AadeMyData\Factories;

use Firebed\AadeMyData\Enums\FeesPercentCategory;
use Firebed\AadeMyData\Enums\InvoiceDetailType;
use Firebed\AadeMyData\Enums\OtherTaxesPercentCategory;
use Firebed\AadeMyData\Enums\RecType;
use Firebed\AadeMyData\Enums\StampCategory;
use Firebed\AadeMyData\Enums\UnitMeasurement;
use Firebed\AadeMyData\Enums\VatCategory;
use Firebed\AadeMyData\Enums\VatExemption;
use Firebed\AadeMyData\Enums\WithheldPercentCategory;
use Firebed\AadeMyData\Models\ExpensesClassification;
use Firebed\AadeMyData\Models\IncomeClassification;
use Firebed\AadeMyData\Models\InvoiceDetails;
use Firebed\AadeMyData\Models\Ship;

/**
 * @extends Factory<InvoiceDetails>
 */
class InvoiceDetailsFactory extends Factory
{
    public function definition(): array
    {
        return [
            'lineNumber'                   => fake()->unique()->numberBetween(1, 100),
            'recType'                      => fake()->randomElement(RecType::cases())->value,
            'TaricNo'                      => fake()->bothify('???###'),
            'itemCode'                     => fake()->bothify('??##??'),
            'itemDescr'                    => fake()->text(),
            'fuelCode'                     => fake()->boolean(),
            'quantity'                     => fake()->randomDigitNotZero(),
            'measurementUnit'              => fake()->randomElement(UnitMeasurement::cases())->value,
            'invoiceDetailType'            => fake()->randomElement(InvoiceDetailType::cases())->value,
            'netValue'                     => fake()->randomFloat(2, 10, 100),
            'vatCategory'                  => fake()->randomElement(VatCategory::cases())->value,
            'vatAmount'                    => fake()->randomFloat(2, 10, 100),
            'vatExemptionCategory'         => fake()->randomElement(VatExemption::cases())->value,
            'dienergia'                    => Ship::factory(),
            'discountOption'               => fake()->boolean(),
            'withheldAmount'               => fake()->randomFloat(2, 10, 100),
            'withheldPercentCategory'      => fake()->randomElement(WithheldPercentCategory::cases())->value,
            'stampDutyAmount'              => fake()->randomFloat(2, 10, 100),
            'stampDutyPercentCategory'     => fake()->randomElement(StampCategory::cases())->value,
            'feesAmount'                   => fake()->randomFloat(2, 10, 100),
            'feesPercentCategory'          => fake()->randomElement(FeesPercentCategory::cases())->value,
            'otherTaxesPercentCategory'    => fake()->randomElement(OtherTaxesPercentCategory::cases())->value,
            'otherTaxesAmount'             => fake()->randomFloat(2, 10, 100),
            'deductionsAmount'             => fake()->randomFloat(2, 10, 100),
            'lineComments'                 => fake()->text(),
            'incomeClassification'         => IncomeClassification::factory(),
            'expensesClassification'       => ExpensesClassification::factory(),
            'quantity15'                   => fake()->randomFloat(2, 10, 50),
            'otherMeasurementUnitQuantity' => fake()->randomDigitNotZero(),
            'otherMeasurementUnitTitle'    => fake()->text(),
        ];
    }
}