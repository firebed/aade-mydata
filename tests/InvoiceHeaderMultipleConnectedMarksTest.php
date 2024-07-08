<?php

namespace Tests;

use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoiceHeader;
use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;

class InvoiceHeaderMultipleConnectedMarksTest extends TestCase
{
    use HandlesInvoiceXml;

    public function test_it_converts_single_invoice_header_mcp_to_xml(): void
    {
        $invoice = Invoice::factory()->make();

        $header = $invoice->getInvoiceHeader();
        $headerXml = $this->toXML($invoice)->InvoicesDoc->invoice->invoiceHeader;

        $this->assertIsArray($header->getMultipleConnectedMarks());
        $this->assertEquals($header->getMultipleConnectedMarks()[0], $headerXml->multipleConnectedMarks);
    }

    public function test_it_converts_multiple_invoice_header_mcp_to_xml(): void
    {
        $header = InvoiceHeader::factory()
            ->state(['multipleConnectedMarks' => [8000000165487234, 8000000165487568, 8000000165487101]])
            ->make();

        $invoice = Invoice::factory()->make();
        $invoice->setInvoiceHeader($header);

        $headerXml = $this->toXML($invoice)->InvoicesDoc->invoice->invoiceHeader;

        $this->assertEquals($header->getMultipleConnectedMarks()[0], $headerXml->multipleConnectedMarks[0]);
        $this->assertEquals($header->getMultipleConnectedMarks()[1], $headerXml->multipleConnectedMarks[1]);
        $this->assertEquals($header->getMultipleConnectedMarks()[2], $headerXml->multipleConnectedMarks[2]);
    }

    public function test_it_converts_xml_to_invoice_header(): void
    {
        $header = $this->getInvoiceFromXml()->getInvoiceHeader();

        $this->assertCount(3, $header->getMultipleConnectedMarks());
        $this->assertEquals(8000000165487234, $header->getMultipleConnectedMarks()[0]);
        $this->assertEquals(8000000165487568, $header->getMultipleConnectedMarks()[1]);
        $this->assertEquals(8000000165487101, $header->getMultipleConnectedMarks()[2]);
    }
}