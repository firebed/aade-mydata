<?php

namespace Firebed\AadeMyData\Factories;

use Firebed\AadeMyData\Models\Address;

/**
 * @extends Factory<Address>
 */
class AddressFactory extends Factory
{
    public function definition(): array
    {
        return [
            'street'     => fake()->streetName,
            'number'     => fake()->buildingNumber,
            'postalCode' => fake()->postcode,
            'city'       => fake()->city
        ];
    }}