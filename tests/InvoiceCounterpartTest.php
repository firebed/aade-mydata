<?php

namespace Tests;

use Firebed\AadeMyData\Models\Invoice;
use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;

class InvoiceCounterpartTest extends TestCase
{
    use HandlesInvoiceXml;

    public function test_invoice_counterpart_xml_is_built()
    {
        $invoice = Invoice::factory()->make();

        $counterpart = $invoice->getCounterpart();
        $counterpartXml = $this->toXML($invoice)->InvoicesDoc->invoice->counterpart;

        $this->assertCount(8, $counterpartXml);
        $this->assertEquals($counterpart->getVatNumber(), $counterpartXml->vatNumber);
        $this->assertEquals($counterpart->getCountry(), $counterpartXml->country);
        $this->assertEquals($counterpart->getBranch(), $counterpartXml->branch);
        $this->assertEquals($counterpart->getName(), $counterpartXml->name);
        $this->assertEquals($counterpart->getDocumentIdNo(), $counterpartXml->documentIdNo);
        $this->assertEquals($counterpart->getSupplyAccountNo(), $counterpartXml->supplyAccountNo);
        $this->assertEquals($counterpart->getCountryDocumentId(), $counterpartXml->countryDocumentId);

        // Address
        $address = $counterpart->getAddress();
        $addressXml = $counterpartXml->address;
        $this->assertCount(4, $addressXml);
        $this->assertEquals($address->getStreet(), $addressXml->street);
        $this->assertEquals($address->getNumber(), $addressXml->number);
        $this->assertEquals($address->getPostalCode(), $addressXml->postalCode);
        $this->assertEquals($address->getCity(), $addressXml->city);
    }

    public function test_invoice_counterpart_xml_is_parsed()
    {
        $invoice = $this->getInvoiceFromXml();

        $counterpart = $invoice->getCounterpart();
        $this->assertCount(8, $counterpart->attributes());
        $this->assertEquals('999999999', $counterpart->getVatNumber());
        $this->assertEquals('GR', $counterpart->getCountry());
        $this->assertEquals(0, $counterpart->getBranch());
        $this->assertEquals('Παπαδόπουλος ΑΕ', $counterpart->getName());
        $this->assertEquals('MMM123N', $counterpart->getDocumentIdNo());
        $this->assertEquals('809778544', $counterpart->getSupplyAccountNo());
        $this->assertEquals('GR', $counterpart->getCountryDocumentId());

        $address = $counterpart->getAddress();
        $this->assertCount(4, $address->attributes());
        $this->assertEquals('Τσιμισκή', $address->getStreet());
        $this->assertEquals('52A', $address->getNumber());
        $this->assertEquals('33333', $address->getPostalCode());
        $this->assertEquals('Χανιά', $address->getCity());
    }
}