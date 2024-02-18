<?php

namespace Firebed\AadeMyData\Factories;

use Firebed\AadeMyData\Models\Address;
use Firebed\AadeMyData\Models\OtherDeliveryNoteHeader;

/**
 * @extends Factory<OtherDeliveryNoteHeader>
 */
class OtherDeliveryNoteHeaderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'loadingAddress'         => Address::factory(),
            'deliveryAddress'        => Address::factory(),
            'startShippingBranch'    => fake()->randomDigitNotNull(),
            'completeShippingBranch' => fake()->randomDigitNotNull(),
        ];
    }
}