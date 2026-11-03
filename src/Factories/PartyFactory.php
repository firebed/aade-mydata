<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Factories;

use Firebed\AadeMyData\Models\Address;
use Firebed\AadeMyData\Models\Party;

/**
 * @template TParty of Party
 *
 * @extends Factory<TParty>
 */
class PartyFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'vatNumber' => fake()->numerify('#########'),
            'country' => fake()->randomElement(['GR', 'CY', 'IT', 'DE', 'BG']),
            'branch' => fake()->randomDigitNotNull(),
            'name' => fake()->company(),
            'documentIdNo' => fake()->bothify(),
            'supplyAccountNo' => fake()->numerify('#########'),
            'countryDocumentId' => fake()->randomElement(['GR', 'CY', 'IT', 'DE', 'BG']),
            'address' => Address::factory(),
        ];
    }
}
