<?php

namespace Firebed\AadeMyData\Factories;

use Firebed\AadeMyData\Models\ExpensesClassification;
use Firebed\AadeMyData\Models\IncomeClassification;
use Firebed\AadeMyData\Models\InvoiceSummary;

/**
 * @extends Factory<InvoiceSummary>
 */
class InvoiceSummaryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'totalNetValue'          => fake()->randomFloat(2, 10, 100),
            'totalVatAmount'         => fake()->randomFloat(2, 10, 100),
            'totalWithheldAmount'    => fake()->randomFloat(2, 10, 100),
            'totalFeesAmount'        => fake()->randomFloat(2, 10, 100),
            'totalStampDutyAmount'   => fake()->randomFloat(2, 10, 100),
            'totalOtherTaxesAmount'  => fake()->randomFloat(2, 10, 100),
            'totalDeductionsAmount'  => fake()->randomFloat(2, 10, 100),
            'totalGrossValue'        => fake()->randomFloat(2, 10, 100),
            'incomeClassification'   => IncomeClassification::factory(),
            'expensesClassification' => ExpensesClassification::factory(),
        ];
    }
}