<?php

namespace Tests;

use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Enums\InvoiceVariationType;
use Firebed\AadeMyData\Enums\MovePurpose;
use Firebed\AadeMyData\Enums\SpecialInvoiceCategory;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoiceHeader;
use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;

class InvoiceHeaderTest extends TestCase
{
    use HandlesInvoiceXml;

    public function test_it_converts_invoice_header_to_xml(): void
    {
        $header = new InvoiceHeader();
        $header->setSeries('E');
        $header->setAa(25);
        $header->setIssueDate('2024-02-12');
        $header->setInvoiceType(InvoiceType::TYPE_1_1);
        $header->setVatPaymentSuspension(true);
        $header->setCurrency('EUR');
        $header->setExchangeRate(1.1);
        $header->addCorrelatedInvoice(123);
        $header->addCorrelatedInvoice(456);
        $header->addCorrelatedInvoice(789);
        $header->setSelfPricing(true);
        $header->setDispatchDate('2024-02-12');
        $header->setDispatchTime('20:10');
        $header->setVehicleNumber('KLM1234');
        $header->setMovePurpose(MovePurpose::TYPE_2);
        $header->setFuelInvoice(true);
        $header->setMovePurpose(MovePurpose::TYPE_2);
        $header->setSpecialInvoiceCategory(SpecialInvoiceCategory::TYPE_3);
        $header->setInvoiceVariationType(InvoiceVariationType::TYPE_3);
        $header->setIsDeliveryNote(true);
        $header->setOtherMovePurposeTitle('Έκθεση');
        $header->setThirdPartyCollection(true);

        $invoice = new Invoice();
        $invoice->setInvoiceHeader($header);

        $xml = $this->toXML($invoice)->InvoicesDoc->invoice;

        $header = $xml->invoiceHeader;
        $this->assertEquals('E', $header->series);
        $this->assertEquals(25, $header->aa);
        $this->assertEquals('2024-02-12', $header->issueDate);
        $this->assertEquals(InvoiceType::TYPE_1_1->value, $header->invoiceType);
        $this->assertEquals('EUR', $header->currency);
        $this->assertEquals(1.1, $header->exchangeRate);
        $this->assertEquals(123, $header->correlatedInvoices[0]);
        $this->assertEquals(456, $header->correlatedInvoices[1]);
        $this->assertEquals(789, $header->correlatedInvoices[2]);
        $this->assertEquals('true', $header->selfPricing);
        $this->assertEquals('2024-02-12', $header->dispatchDate);
        $this->assertEquals('20:10', $header->dispatchTime);
        $this->assertEquals('KLM1234', $header->vehicleNumber);
        $this->assertEquals(MovePurpose::TYPE_2->value, $header->movePurpose);
        $this->assertEquals('true', $header->fuelInvoice);
        $this->assertEquals(SpecialInvoiceCategory::TYPE_3->value, $header->specialInvoiceCategory);
        $this->assertEquals(InvoiceVariationType::TYPE_3->value, $header->invoiceVariationType);
        $this->assertEquals('true', $header->isDeliveryNote);
        $this->assertEquals('Έκθεση', $header->otherMovePurposeTitle);
        $this->assertEquals('true', $header->thirdPartyCollection);
    }

    public function test_it_converts_xml_to_invoice_header(): void
    {
        $invoice = $this->getInvoiceFromXml();

        $header = $invoice->getInvoiceHeader();
        $this->assertEquals('A', $header->getSeries());
        $this->assertEquals(101, $header->getAa());
        $this->assertEquals('2020-04-08', $header->getIssueDate());
        $this->assertEquals(InvoiceType::TYPE_1_1->value, $header->getInvoiceType());
        $this->assertFalse($header->isVatPaymentSuspension());
        $this->assertEquals('EUR', $header->getCurrency());
        $this->assertTrue($header->isSelfPricing());

        $this->assertCount(3, $header->getCorrelatedInvoices());
        $this->assertEquals(1234, $header->getCorrelatedInvoices()[0]);
        $this->assertEquals(4568, $header->getCorrelatedInvoices()[1]);
        $this->assertEquals(9101, $header->getCorrelatedInvoices()[2]);
    }
}