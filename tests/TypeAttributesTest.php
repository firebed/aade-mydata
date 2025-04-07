<?php

namespace Tests;

use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoiceHeader;
use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;

class TypeAttributesTest extends TestCase
{
    use HandlesInvoiceXml;

    public function test_type_constructor()
    {
        for ($i = 0; $i < 10; $i++) {
            $invoice = Invoice::factory()->make();
            $invoiceXml = $this->toXML($invoice)->saveXML();

            $invoiceCopy = new Invoice($invoice->toArray());
            $invoiceCopyXml = $this->toXML($invoiceCopy)->saveXML();

            $this->assertEquals($invoiceXml, $invoiceCopyXml);
        }
    }

    public function test_type_constructor_with_mixed_attributes()
    {
        $invoice = Invoice::factory()->make();
        $invoiceXml = $this->toXML($invoice)->saveXML();

        $attributes = $invoice->toArray();
        $attributes['invoiceHeader'] = $invoice->getInvoiceHeader();

        $invoiceCopy = new Invoice($attributes);
        $invoiceCopy->setCounterpart($invoice->getCounterpart());
        $invoiceCopyXml = $this->toXML($invoiceCopy)->saveXML();

        $this->assertEquals($invoiceXml, $invoiceCopyXml);
    }

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