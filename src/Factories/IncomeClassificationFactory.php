<?php

namespace Firebed\AadeMyData\Factories;

use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Models\IncomeClassification;

/**
 * @extends Factory<IncomeClassification>
 */
class IncomeClassificationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'classificationType'     => fake()->randomElement(IncomeClassificationType::cases())->value,
            'classificationCategory' => fake()->randomElement(IncomeClassificationCategory::cases())->value,
            'amount'                 => fake()->randomFloat(2, 0, 1000),
            'id'                     => fake()->numberBetween(1, 100)
        ];
    }
}