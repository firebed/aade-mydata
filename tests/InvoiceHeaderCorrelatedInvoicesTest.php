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

class InvoiceHeaderCorrelatedInvoicesTest extends TestCase
{
    use HandlesInvoiceXml;

    public function test_it_converts_single_invoice_header_correlated_invoice_to_xml(): void
    {
        $invoice = Invoice::factory()->make();

        $header = $invoice->getInvoiceHeader();
        $headerXml = $this->toXML($invoice)->InvoicesDoc->invoice->invoiceHeader;

        $this->assertIsArray($header->getCorrelatedInvoices());
        $this->assertEquals($header->getCorrelatedInvoices()[0], $headerXml->correlatedInvoices);
    }

    public function test_it_converts_multiple_invoice_header_correlated_invoices_to_xml(): void
    {
        $header = InvoiceHeader::factory()
            ->state(['correlatedInvoices' => [8000000165487234, 8000000165487568, 8000000165487101]])
            ->make();

        $invoice = Invoice::factory()->make();
        $invoice->setInvoiceHeader($header);

        $headerXml = $this->toXML($invoice)->InvoicesDoc->invoice->invoiceHeader;

        $this->assertEquals($header->getCorrelatedInvoices()[0], $headerXml->correlatedInvoices[0]);
        $this->assertEquals($header->getCorrelatedInvoices()[1], $headerXml->correlatedInvoices[1]);
        $this->assertEquals($header->getCorrelatedInvoices()[2], $headerXml->correlatedInvoices[2]);
    }

    public function test_it_converts_xml_to_invoice_header(): void
    {
        $header = $this->getInvoiceFromXml()->getInvoiceHeader();

        $this->assertCount(21, $header->attributes());

        $this->assertEquals('A', $header->getSeries());
        $this->assertEquals(101, $header->getAa());
        $this->assertEquals('2020-04-08', $header->getIssueDate());
        $this->assertEquals(InvoiceType::TYPE_1_1, $header->getInvoiceType());
        $this->assertFalse($header->isVatPaymentSuspension());
        $this->assertEquals('EUR', $header->getCurrency());

        $this->assertCount(3, $header->getCorrelatedInvoices());
        $this->assertEquals(8000000165487234, $header->getCorrelatedInvoices()[0]);
        $this->assertEquals(8000000165487568, $header->getCorrelatedInvoices()[1]);
        $this->assertEquals(8000000165487101, $header->getCorrelatedInvoices()[2]);

        $this->assertTrue($header->isSelfPricing());
        $this->assertEquals('2024-02-13', $header->getDispatchDate());
        $this->assertEquals('00:00', $header->getDispatchTime());
        $this->assertEquals('KHB4201', $header->getVehicleNumber());
        $this->assertEquals(MovePurpose::TYPE_19, $header->getMovePurpose());
        $this->assertTrue($header->isFuelInvoice());
        $this->assertEquals(SpecialInvoiceCategory::TYPE_5, $header->getSpecialInvoiceCategory());
        $this->assertEquals(InvoiceVariationType::TYPE_3, $header->getInvoiceVariationType());
    }
}