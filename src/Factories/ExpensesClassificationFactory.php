<?php

namespace Firebed\AadeMyData\Factories;

use Firebed\AadeMyData\Enums\ExpenseClassificationCategory;
use Firebed\AadeMyData\Enums\ExpenseClassificationType;
use Firebed\AadeMyData\Enums\VatExemption;
use Firebed\AadeMyData\Models\ExpensesClassification;

/**
 * @extends Factory<ExpensesClassification>
 */
class ExpensesClassificationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'classificationType' => fake()->randomElement(ExpenseClassificationType::cases()),
            'classificationCategory' => fake()->randomElement(ExpenseClassificationCategory::cases()),
            'amount' => fake()->randomFloat(2, 0, 1000),
            'vatAmount' => fake()->randomFloat(2, 0, 100),
            'vatCategory' => fake()->numberBetween(1, 8),
            'vatExemptionCategory' => fake()->randomElement(VatExemption::cases()),
            'id' => fake()->unique()->numberBetween(1, 100),
        ];
    }
}