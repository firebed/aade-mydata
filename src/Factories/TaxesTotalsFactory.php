<?php

namespace Firebed\AadeMyData\Factories;

use Firebed\AadeMyData\Models\TaxesTotals;
use Firebed\AadeMyData\Models\TaxTotals;

/**
 * @extends Factory<TaxesTotals>
 */
class TaxesTotalsFactory extends Factory
{
    public function definition(): array
    {
        return [
            'taxes' => TaxTotals::factory()
        ];
    }
}