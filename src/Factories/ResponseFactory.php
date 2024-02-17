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
            'invoiceUid'        => $this->faker->sha1(),
            'invoiceMark'       => $this->faker->numerify("800000#########"),
            'paymentMethodMark' => $this->faker->numerify("800000#########"),
            'qrUrl'             => $this->faker->url(),
            'statusCode'        => 'Success'
        ];
    }

    public function cancelled(): self
    {
        return $this->state([
            'cancellationMark' => $this->faker->numerify("800000#########")
        ])->except([
            'index',
            'invoiceUid',
            'invoiceMark',
            'paymentMethodMark',
            'qrUrl'
        ]);
    }
}