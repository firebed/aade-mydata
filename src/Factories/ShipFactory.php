<?php

namespace Firebed\AadeMyData\Factories;

use Firebed\AadeMyData\Models\Ship;

/**
 * @extends Factory<Ship>
 */
class ShipFactory extends Factory
{
    public function definition(): array
    {
        return [
            'applicationId'   => fake()->sha1(),
            'applicationDate' => fake()->dateTimeThisMonth()->format('Y-m-d'),
            'doy'             => fake()->city(),
            'shipID'          => fake()->bothify('#??##??'),
        ];
    }
}