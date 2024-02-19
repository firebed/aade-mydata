<?php

namespace Firebed\AadeMyData\Factories;

use Firebed\AadeMyData\Models\PaymentMethodDetail;

/**
 * @extends Factory<PaymentMethodDetail>
 */
class PaymentMethodsFactory extends Factory
{
    public function definition(): array
    {
        return [
            'paymentMethodDetails' => PaymentMethodDetail::factory()
        ];
    }
}