<?php

namespace Tests;

use Firebed\AadeMyData\Models\Address;
use Firebed\AadeMyData\Models\Counterpart;
use Firebed\AadeMyData\Models\Invoice;
use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;

class InvoiceCounterpartTest extends TestCase
{
    use HandlesInvoiceXml;

    public function test_invoice_counterpart_xml_is_built()
    {
        $address = new Address();
        $address->setStreet('Τσιμισκή');
        $address->setNumber('52A');
        $address->setPostalCode('33333');
        $address->setCity('Χανιά');

        $counterpart = new Counterpart();
        $counterpart->setVatNumber('999999999');
        $counterpart->setCountry('GR');
        $counterpart->setBranch(0);
        $counterpart->setDocumentIdNo('MMM123N');
        $counterpart->setSupplyAccountNo('809778544');
        $counterpart->setAddress($address);

        $invoice = new Invoice();
        $invoice->setCounterpart($counterpart);

        $xml = $this->toXML($invoice)->InvoicesDoc->invoice->counterpart;
        
        $this->assertCount(6, $xml);
        $this->assertEquals('999999999', $xml->get('vatNumber'));
        $this->assertEquals('GR', $xml->get('country'));
        $this->assertEquals('0', $xml->get('branch'));
        $this->assertEquals('MMM123N', $xml->get('documentIdNo'));
        $this->assertEquals('809778544', $xml->get('supplyAccountNo'));

        // Address
        $address = $xml->address;
        $this->assertCount(4, $address);
        $this->assertEquals('33333', $address->get('postalCode'));
        $this->assertEquals('Χανιά', $address->get('city'));
        $this->assertEquals('Τσιμισκή', $address->get('street'));
        $this->assertEquals('52A', $address->get('number'));
    }

    public function test_invoice_counterpart_xml_is_parsed()
    {
        $invoice = $this->getInvoiceFromXml();

        $counterpart = $invoice->getCounterpart();
        $this->assertEquals('999999999', $counterpart->getVatNumber());
        $this->assertEquals('GR', $counterpart->getCountry());
        $this->assertEquals('0', $counterpart->getBranch());
        $this->assertEquals('MMM123N', $counterpart->getDocumentIdNo());
        $this->assertEquals('809778544', $counterpart->getSupplyAccountNo());

        $address = $counterpart->getAddress();
        $this->assertEquals('Τσιμισκή', $address->getStreet());
        $this->assertEquals('52A', $address->getNumber());
        $this->assertEquals('33333', $address->getPostalCode());
        $this->assertEquals('Χανιά', $address->getCity());
    }
}