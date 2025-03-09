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
}