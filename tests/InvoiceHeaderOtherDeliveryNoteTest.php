<?php

namespace Tests;

use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoiceHeader;
use Firebed\AadeMyData\Models\OtherDeliveryNoteHeader;
use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;

class InvoiceHeaderOtherDeliveryNoteTest extends TestCase
{
    use HandlesInvoiceXml;

    public function test_it_converts_invoice_header_other_delivery_note_to_xml(): void
    {
        $otherDeliveryNoteHeader = new OtherDeliveryNoteHeader();
        $otherDeliveryNoteHeader->setLoadingAddress($this->createAddress('Λεωφόρου Στρατού', 28, '12345', 'Ηράκλειο'));
        $otherDeliveryNoteHeader->setDeliveryAddress($this->createAddress('28ης Οκτωβρίου', '325Α', '44552', 'Χανιά'));
        $otherDeliveryNoteHeader->setStartShippingBranch(1);
        $otherDeliveryNoteHeader->setCompleteShippingBranch(2);

        $header = new InvoiceHeader();
        $header->setOtherDeliveryNoteHeader($otherDeliveryNoteHeader);

        $invoice = new Invoice();
        $invoice->setInvoiceHeader($header);

        $xml = $this->toXML($invoice)->InvoicesDoc->invoice->invoiceHeader->otherDeliveryNoteHeader;

        $this->assertEquals('12345', $xml->loadingAddress->postalCode);
        $this->assertEquals('Ηράκλειο', $xml->loadingAddress->city);
        $this->assertEquals('Λεωφόρου Στρατού', $xml->loadingAddress->street);
        $this->assertEquals('28', $xml->loadingAddress->number);

        $this->assertEquals('44552', $xml->deliveryAddress->postalCode);
        $this->assertEquals('Χανιά', $xml->deliveryAddress->city);
        $this->assertEquals('28ης Οκτωβρίου', $xml->deliveryAddress->street);
        $this->assertEquals('325Α', $xml->deliveryAddress->number);

        $this->assertEquals(1, $xml->startShippingBranch);
        $this->assertEquals(2, $xml->completeShippingBranch);
    }

    public function test_it_converts_xml_to_invoice_header_other_delivery_note(): void
    {
        $invoice = $this->getInvoiceFromXml('requested-doc-complete-invoice');

        $deliveryNote = $invoice->getInvoiceHeader()->getOtherDeliveryNoteHeader();

        $loadingAddress = $deliveryNote->getLoadingAddress();
        $this->assertEquals('Τσιμισκή', $loadingAddress->getStreet());
        $this->assertEquals('25', $loadingAddress->getNumber());
        $this->assertEquals('13152', $loadingAddress->getPostalCode());
        $this->assertEquals('Θεσσαλονίκη', $loadingAddress->getCity());

        $deliveryAddress = $deliveryNote->getDeliveryAddress();
        $this->assertEquals('Παπανδρέου', $deliveryAddress->getStreet());
        $this->assertEquals('52', $deliveryAddress->getNumber());
        $this->assertEquals('11255', $deliveryAddress->getPostalCode());
        $this->assertEquals('Αθήνα', $deliveryAddress->getCity());

        $this->assertEquals(1, $deliveryNote->getStartShippingBranch());
        $this->assertEquals(2, $deliveryNote->getCompleteShippingBranch());
    }
}
