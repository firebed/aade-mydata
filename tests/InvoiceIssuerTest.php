<?php

namespace Tests;

use Firebed\AadeMyData\Models\Invoice;
use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;

class InvoiceIssuerTest extends TestCase
{
    use HandlesInvoiceXml;

    public function test_it_converts_invoice_issuer_to_xml()
    {
        $invoice = Invoice::factory()->make();

        $issuer = $invoice->getIssuer();
        $issuerXml = $this->toXML($invoice)->InvoicesDoc->invoice->issuer;

        $this->assertCount(8, $issuerXml);
        $this->assertEquals($issuer->getVatNumber(), $issuerXml->vatNumber);
        $this->assertEquals($issuer->getCountry(), $issuerXml->country);
        $this->assertEquals($issuer->getBranch(), $issuerXml->branch);
        $this->assertEquals($issuer->getName(), $issuerXml->name);
        $this->assertEquals($issuer->getDocumentIdNo(), $issuerXml->documentIdNo);
        $this->assertEquals($issuer->getSupplyAccountNo(), $issuerXml->supplyAccountNo);
        $this->assertEquals($issuer->getCountryDocumentId(), $issuerXml->countryDocumentId);

        // Address
        $address = $issuer->getAddress();
        $addressXml = $issuerXml->address;
        $this->assertCount(4, $addressXml);
        $this->assertEquals($address->getStreet(), $addressXml->street);
        $this->assertEquals($address->getNumber(), $addressXml->number);
        $this->assertEquals($address->getPostalCode(), $addressXml->postalCode);
        $this->assertEquals($address->getCity(), $addressXml->city);
    }

    public function test_it_converts_xml_to_issuer()
    {
        $invoice = $this->getInvoiceFromXml();

        $issuer = $invoice->getIssuer();
        $this->assertCount(8, $issuer->attributes());
        $this->assertEquals('888888888', $issuer->getVatNumber());
        $this->assertEquals('GR', $issuer->getCountry());
        $this->assertEquals(1, $issuer->getBranch());
        $this->assertEquals('Ακμή ΑΕ', $issuer->getName());
        $this->assertEquals('AAA5454', $issuer->getDocumentIdNo());
        $this->assertEquals('7845547781', $issuer->getSupplyAccountNo());
        $this->assertEquals('GR', $issuer->getCountryDocumentId());

        $address = $issuer->getAddress();
        $this->assertCount(4, $address->attributes());
        $this->assertEquals('28ης Οκτωβρίου', $address->getStreet());
        $this->assertEquals('54A', $address->getNumber());
        $this->assertEquals('44458', $address->getPostalCode());
        $this->assertEquals('Ηράκλειο', $address->getCity());
    }
}