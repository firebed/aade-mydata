<?php

namespace Tests;

use Firebed\AadeMyData\Enums\InvoiceType;
use PHPUnit\Framework\TestCase;

class InvoiceTypeTest extends TestCase
{
    public function test_unit_of_measurement(): void
    {
        $this->assertTrue(InvoiceType::TYPE_1_1->supportsUnitOfMeasurement());
        $this->assertFalse(InvoiceType::TYPE_2_1->supportsUnitOfMeasurement());
    }

    public function test_supports_delivery_note_includes_v2_0_2_types(): void
    {
        // Newly allowed to be a delivery note in v2.0.2
        $this->assertTrue(InvoiceType::TYPE_1_4->supportsDeliveryNote());
        $this->assertTrue(InvoiceType::TYPE_3_1->supportsDeliveryNote());
        $this->assertTrue(InvoiceType::TYPE_3_2->supportsDeliveryNote());
        $this->assertTrue(InvoiceType::TYPE_11_5->supportsDeliveryNote());

        // Pre-existing delivery-note types remain supported
        $this->assertTrue(InvoiceType::TYPE_1_1->supportsDeliveryNote());
        $this->assertTrue(InvoiceType::TYPE_11_1->supportsDeliveryNote());

        // A type that is not a delivery note
        $this->assertFalse(InvoiceType::TYPE_6_1->supportsDeliveryNote());
    }
}