<?php

namespace Firebed\AadeMyData\Factories;

use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Enums\InvoiceVariationType;
use Firebed\AadeMyData\Enums\MovePurpose;
use Firebed\AadeMyData\Enums\SpecialInvoiceCategory;
use Firebed\AadeMyData\Models\EntityType;
use Firebed\AadeMyData\Models\InvoiceHeader;
use Firebed\AadeMyData\Models\OtherDeliveryNoteHeader;

/**
 * @extends Factory<InvoiceHeader>
 */
class InvoiceHeaderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'series' => strtoupper(fake()->bothify('??')),
            'aa' => fake()->randomNumber(2),
            'issueDate' => fake()->dateTimeThisMonth()->format('Y-m-d'),
            'invoiceType' => fake()->randomElement(InvoiceType::cases()),
            'vatPaymentSuspension' => fake()->boolean(),
            'currency' => fake()->randomElement(['EUR', 'USD', 'GBP']),
            'exchangeRate' => fake()->randomFloat(1, 1, 30),
            'correlatedInvoices' => fake()->numerify('800000#########'),
            'selfPricing' => fake()->boolean(),
            'dispatchDate' => fake()->dateTimeThisMonth()->format('Y-m-d'),
            'dispatchTime' => fake()->time(),
            'vehicleNumber' => fake()->bothify('???####'),
            'movePurpose' => fake()->randomElement(MovePurpose::cases()),
            'fuelInvoice' => fake()->boolean(),
            'specialInvoiceCategory' => fake()->randomElement(SpecialInvoiceCategory::cases()),
            'invoiceVariationType' => fake()->randomElement(InvoiceVariationType::cases()),
            'otherCorrelatedEntities' => EntityType::factory(2),
            'otherDeliveryNoteHeader' => OtherDeliveryNoteHeader::factory(),
            'isDeliveryNote' => fake()->boolean(),
            'otherMovePurposeTitle' => fake()->words(3, true),
            'thirdPartyCollection' => fake()->boolean(),
            'multipleConnectedMarks' => fake()->numerify('800000#########'),
            'tableAA' => fake()->word(),
            'totalCancelDeliveryOrders' => fake()->boolean(),
        ];
    }
}