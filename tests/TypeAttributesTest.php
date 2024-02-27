<?php

namespace Tests;

use Firebed\AadeMyData\Models\InvoiceHeader;
use PHPUnit\Framework\TestCase;

class TypeAttributesTest extends TestCase
{
    public function test_attributes_are_sorted()
    {
        $invoiceHeader = InvoiceHeader::factory()->make();

        // Get the initial attributes
        $attributes = $invoiceHeader->attributes();

        // Shuffle the attributes
        $keys = array_keys($attributes);
        shuffle($keys);

        // Set back the attributes to the invoice header
        $invoiceHeader->setAttributes(array_merge(array_flip($keys), $attributes));

        // Assert that the attributes are sorted
        $this->assertSame($invoiceHeader->getExpectedOrder(), array_keys($invoiceHeader->sortedAttributes()));
    }
}