<?php

namespace Firebed\AadeMyData\Factories;

use Firebed\AadeMyData\Models\Address;
use Firebed\AadeMyData\Models\Party;

/**
 * @extends Factory<Party>
 */
class PartyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'vatNumber'         => fake()->numerify("#########"),
            'country'           => fake()->randomElement(['GR', 'CY', 'IT', 'DE', 'BG']),
            'branch'            => fake()->randomDigitNotNull(),
            'name'              => fake()->company(),
            'documentIdNo'      => fake()->bothify(),
            'supplyAccountNo'   => fake()->numerify("#########"),
            'countryDocumentId' => fake()->randomElement(['GR', 'CY', 'IT', 'DE', 'BG']),
            'address'           => Address::factory(),
        ];
    }
}