<?php

namespace Tests;

use Firebed\AadeMyData\Models\Address;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\Issuer;
use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;

class InvoiceIssuerTest extends TestCase
{
    use HandlesInvoiceXml;

    public function test_invoice_issuer_xml_is_built()
    {
        $address = new Address();
        $address->setStreet('Τσιμισκή');
        $address->setNumber('52A');
        $address->setPostalCode('33333');
        $address->setCity('Χανιά');

        $issuer = new Issuer();
        $issuer->setVatNumber('999999999');
        $issuer->setCountry('GR');
        $issuer->setBranch(0);
        $issuer->setDocumentIdNo('MMM123N');
        $issuer->setSupplyAccountNo('809778544');
        $issuer->setAddress($address);

        $invoice = new Invoice();
        $invoice->setIssuer($issuer);

        $xml = $this->toXML($invoice)->InvoicesDoc->invoice;

        $issuer = $xml->issuer;
        $this->assertEquals('999999999', $issuer->vatNumber);
        $this->assertEquals('GR', $issuer->country);
        $this->assertEquals('0', $issuer->branch);
        $this->assertEquals('MMM123N', $issuer->documentIdNo);
        $this->assertEquals('809778544', $issuer->supplyAccountNo);

        // Address
        $address = $issuer->address;
        $this->assertEquals('33333', $address->postalCode);
        $this->assertEquals('Χανιά', $address->city);
        $this->assertEquals('Τσιμισκή', $address->street);
        $this->assertEquals('52A', $address->number);
    }

    public function test_invoice_issuer_xml_is_parsed()
    {
        $invoice = $this->getInvoiceFromXml();

        $issuer = $invoice->getIssuer();
        $this->assertEquals('888888888', $issuer->getVatNumber());
        $this->assertEquals('GR', $issuer->getCountry());
        $this->assertEquals('1', $issuer->getBranch());
        $this->assertEquals('AAA5454', $issuer->getDocumentIdNo());
        $this->assertEquals('7845547781', $issuer->getSupplyAccountNo());

        $address = $issuer->getAddress();
        $this->assertEquals('28ης Οκτωβρίου', $address->getStreet());
        $this->assertEquals('54A', $address->getNumber());
        $this->assertEquals('44458', $address->getPostalCode());
        $this->assertEquals('Ηράκλειο', $address->getCity());
    }
}