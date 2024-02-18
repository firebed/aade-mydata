<?php

namespace Firebed\AadeMyData\Factories;

use Firebed\AadeMyData\Models\PaymentMethodDetail;

/**
 * @extends Factory<PaymentMethodDetail>
 */
class PaymentMethodDetailFactory extends Factory
{
    public function definition(): array
    {
        return [
            'type'              => rand(800_000_000_000_000, 800_000_999_999_999),
            'amount'            => fake()->randomFloat(2, 20, 100),
            'paymentMethodInfo' => fake()->text(100),
            'tipAmount'         => fake()->randomFloat(2, 0, 10),
            'transactionId'     => 'Success'
        ];
    }

    public function withECRSignature(): self
    {
        return $this->state([
            'ECRToken' => [
                'SigningAuthor' => fake()->sha1(),
                'SessionNumber' => fake()->numerify('######'),
            ]
        ]);
    }

    public function withProviderSignature(): self
    {
        return $this->state([
            'ProvidersSignature' => [
                'SigningAuthor' => fake()->sha1(),
                'SessionNumber' => fake()->bothify("??##??"),
            ]
        ]);
    }
}