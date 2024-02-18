<?php

namespace Firebed\AadeMyData\Factories;

use Firebed\AadeMyData\Models\Response;

/**
 * @extends Factory<Response>
 */
class ResponseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'index'             => 1,
            'invoiceUid'        => fake()->sha1(),
            'invoiceMark'       => fake()->numerify("800000#########"),
            'paymentMethodMark' => fake()->numerify("800000#########"),
            'qrUrl'             => fake()->url(),
            'statusCode'        => 'Success'
        ];
    }

    public function cancelled(): self
    {
        return $this->state([
            'cancellationMark' => fake()->numerify("800000#########")
        ])->except([
            'index',
            'invoiceUid',
            'invoiceMark',
            'paymentMethodMark',
            'qrUrl'
        ]);
    }
}