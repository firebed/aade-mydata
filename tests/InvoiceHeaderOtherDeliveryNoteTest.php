<?php

namespace Tests;

use Firebed\AadeMyData\Models\Invoice;
use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;

class InvoiceHeaderOtherDeliveryNoteTest extends TestCase
{
    use HandlesInvoiceXml;

    public function test_it_converts_invoice_header_other_delivery_note_to_xml(): void
    {
        $invoice = Invoice::factory()->make();

        $note = $invoice->getInvoiceHeader()->getOtherDeliveryNoteHeader();
        $noteXml = $this->toXML($invoice)->InvoicesDoc->invoice->invoiceHeader->otherDeliveryNoteHeader;

        // Loading Address
        $this->assertEquals($note->getLoadingAddress()->getStreet(), $noteXml->loadingAddress->street);
        $this->assertEquals($note->getLoadingAddress()->getNumber(), $noteXml->loadingAddress->number);
        $this->assertEquals($note->getLoadingAddress()->getPostalCode(), $noteXml->loadingAddress->postalCode);
        $this->assertEquals($note->getLoadingAddress()->getCity(), $noteXml->loadingAddress->city);

        // Delivery Address
        $this->assertEquals($note->getDeliveryAddress()->getStreet(), $noteXml->deliveryAddress->street);
        $this->assertEquals($note->getDeliveryAddress()->getNumber(), $noteXml->deliveryAddress->number);
        $this->assertEquals($note->getDeliveryAddress()->getPostalCode(), $noteXml->deliveryAddress->postalCode);
        $this->assertEquals($note->getDeliveryAddress()->getCity(), $noteXml->deliveryAddress->city);

        $this->assertEquals($note->getStartShippingBranch(), $noteXml->startShippingBranch);
        $this->assertEquals($note->getCompleteShippingBranch(), $noteXml->completeShippingBranch);
    }

    public function test_it_converts_xml_to_invoice_header_other_delivery_note(): void
    {
        $deliveryNote = $this->getInvoiceFromXml()->getInvoiceHeader()->getOtherDeliveryNoteHeader();

        // Loading Address
        $loadingAddress = $deliveryNote->getLoadingAddress();
        $this->assertEquals('Τσιμισκή', $loadingAddress->getStreet());
        $this->assertEquals('25', $loadingAddress->getNumber());
        $this->assertEquals('13152', $loadingAddress->getPostalCode());
        $this->assertEquals('Θεσσαλονίκη', $loadingAddress->getCity());

        // Delivery Address
        $deliveryAddress = $deliveryNote->getDeliveryAddress();
        $this->assertEquals('Παπανδρέου', $deliveryAddress->getStreet());
        $this->assertEquals('52', $deliveryAddress->getNumber());
        $this->assertEquals('11255', $deliveryAddress->getPostalCode());
        $this->assertEquals('Αθήνα', $deliveryAddress->getCity());

        $this->assertEquals(1, $deliveryNote->getStartShippingBranch());
        $this->assertEquals(2, $deliveryNote->getCompleteShippingBranch());
    }
}
