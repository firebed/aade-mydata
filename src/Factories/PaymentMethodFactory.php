<?php

namespace Firebed\AadeMyData\Factories;

use Firebed\AadeMyData\Models\PaymentMethod;
use Firebed\AadeMyData\Models\PaymentMethodDetail;

/**
 * @extends Factory<PaymentMethod>
 */
class PaymentMethodFactory extends Factory
{
    public function definition(): array
    {
        return [
            'invoiceMark'          => fake()->numerify("800000#########"),
            'entityVatNumber'      => fake()->numerify("#########"),
            'paymentMethodDetails' => PaymentMethodDetail::factory(),
        ];
    }
}