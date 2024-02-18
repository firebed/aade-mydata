<?php

namespace Firebed\AadeMyData\Factories;

use Firebed\AadeMyData\Models\TransportDetail;

/**
 * @extends Factory<TransportDetail>
 */
class TransportDetailFactory extends Factory
{
    public function definition(): array
    {
        return [
            'vehicleNumber' => fake()->bothify("???####"),
        ];
    }
}