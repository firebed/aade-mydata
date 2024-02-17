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
            'invoiceMark' => $this->faker->numerify("800000######"),
            'entityVatNumber' => $this->faker->numerify("#########"),
        ];
    }

    public function withPaymentMethodDetails(Factory $factory = null): self
    {
        $factory ??= PaymentMethodDetail::factory();
        $paymentMethodDetails = $this->state['paymentMethodDetails'] ?? [];
        $paymentMethodDetails[] = $factory->make()->attributes();
        
        return $this->state(compact('paymentMethodDetails'));
    }
}