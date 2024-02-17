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
            'amount'            => $this->faker->randomFloat(2, 20, 100),
            'paymentMethodInfo' => $this->faker->text(100),
            'tipAmount'         => $this->faker->randomFloat(2, 0, 10),
            'transactionId'     => 'Success'
        ];
    }

    public function withECRSignature(): self
    {
        return $this->state([
            'ECRToken' => [
                'SigningAuthor' => $this->faker->sha1(),
                'SessionNumber' => $this->faker->numerify('######'),
            ]
        ]);
    }

    public function withProviderSignature(): self
    {
        return $this->state([
            'ProvidersSignature' => [
                'SigningAuthor' => $this->faker->sha1(),
                'SessionNumber' => $this->faker->bothify("??##??"),
            ]
        ]);
    }
}