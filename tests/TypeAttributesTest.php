<?php

namespace Tests;

use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoiceHeader;
use Firebed\AadeMyData\Models\PaymentMethodDetail;
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

    public function test_list_type_attribute_accepts_a_plain_list_of_objects(): void
    {
        $invoice = new Invoice(['paymentMethods' => [
            new PaymentMethodDetail(['type' => 3, 'amount' => 10]),
            new PaymentMethodDetail(['type' => 5, 'amount' => 20]),
        ]]);

        $this->assertCount(2, $invoice->getPaymentMethods());
        $this->assertEquals(20, $invoice->getPaymentMethods()[1]->getAmount());
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

        // Assert that the set attributes come out in the expected order. Optional
        // fields that are not populated (e.g. the v2.0.2 receiving-note fields) are
        // skipped so the assertion targets ordering, not completeness.
        $expectedOrder = array_values(array_filter(
            $invoiceHeader->getExpectedOrder(),
            fn ($key) => array_key_exists($key, $invoiceHeader->attributes())
        ));

        $this->assertSame($expectedOrder, array_keys($invoiceHeader->sortedAttributes()));
    }
}