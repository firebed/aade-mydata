<?php

namespace Firebed\AadeMyData\Factories;

use Firebed\AadeMyData\Enums\TaxType;
use Firebed\AadeMyData\Enums\WithheldPercentCategory;
use Firebed\AadeMyData\Models\TaxTotals;

/**
 * @extends Factory<TaxTotals>
 */
class TaxTotalsFactory extends Factory
{
    public function definition(): array
    {
        return [
            'taxType'         => fake()->randomElement(TaxType::cases())->value,
            'taxCategory'     => fake()->randomElement(WithheldPercentCategory::cases())->value,
            'underlyingValue' => fake()->randomFloat(2, 0, 100),
            'taxAmount'       => fake()->randomFloat(2, 0, 20),
            'id'              => fake()->randomDigitNotZero(),
        ];
    }
}