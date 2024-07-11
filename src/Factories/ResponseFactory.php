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
            'index' => 1,
            'invoiceUid' => strtoupper(fake()->sha1()),
            'authenticationCode' => strtoupper(fake()->sha1()),
            'invoiceMark' => fake()->numerify("900000#########"),
            'paymentMethodMark' => fake()->numerify("900000#########"),
            'qrUrl' => fake()->url(),
            'statusCode' => 'Success',
            'receptionEmails' => [
                'email' => [
                    fake()->email(),
                    fake()->email(),
                ]
            ],
        ];
    }

    public function cancelled(): self
    {
        return $this->state([
            'cancellationMark' => fake()->numerify("900000#########")
        ])->except([
            'index',
            'invoiceUid',
            'invoiceMark',
            'paymentMethodMark',
            'qrUrl'
        ]);
    }
}