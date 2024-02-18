<?php

use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\TaxTotals;
use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;

class InvoiceTaxesTotalsTest extends TestCase
{
    use HandlesInvoiceXml;
    
    public function test_it_converts_single_invoice_tax_totals_to_xml(): void
    {
        $invoice = Invoice::factory()->make();

        $taxesTotals = $invoice->getTaxesTotals();
        $taxesTotalsXml = $this->toXML($invoice)->InvoicesDoc->invoice->taxesTotals->taxes;
        
        $this->assertEquals($taxesTotals[0]->getTaxType(), $taxesTotalsXml->taxType);
        $this->assertEquals($taxesTotals[0]->getTaxCategory(), $taxesTotalsXml->taxCategory);
        $this->assertEquals($taxesTotals[0]->getUnderlyingValue(), $taxesTotalsXml->underlyingValue);
        $this->assertEquals($taxesTotals[0]->getTaxAmount(), $taxesTotalsXml->taxAmount);
        $this->assertEquals($taxesTotals[0]->getId(), $taxesTotalsXml->id);
    }

    public function test_it_converts_multiple_invoice_tax_totals_to_xml(): void
    {
        $invoice = Invoice::factory()
            ->state(['taxesTotals' => TaxTotals::factory(2)])
            ->make();

        $taxesTotals = $invoice->getTaxesTotals();
        $taxesTotalsXml = $this->toXML($invoice)->InvoicesDoc->invoice->taxesTotals->taxes;
        
        $this->assertCount(2, $taxesTotals);
        $this->assertCount(2, $taxesTotalsXml);
        
        $this->assertEquals($taxesTotals[0]->getTaxType(), $taxesTotalsXml[0]->taxType);
        $this->assertEquals($taxesTotals[0]->getTaxCategory(), $taxesTotalsXml[0]->taxCategory);
        $this->assertEquals($taxesTotals[0]->getUnderlyingValue(), $taxesTotalsXml[0]->underlyingValue);
        $this->assertEquals($taxesTotals[0]->getTaxAmount(), $taxesTotalsXml[0]->taxAmount);
        $this->assertEquals($taxesTotals[0]->getId(), $taxesTotalsXml[0]->id);


        $this->assertEquals($taxesTotals[1]->getTaxType(), $taxesTotalsXml[1]->taxType);
        $this->assertEquals($taxesTotals[1]->getTaxCategory(), $taxesTotalsXml[1]->taxCategory);
        $this->assertEquals($taxesTotals[1]->getUnderlyingValue(), $taxesTotalsXml[1]->underlyingValue);
        $this->assertEquals($taxesTotals[1]->getTaxAmount(), $taxesTotalsXml[1]->taxAmount);
        $this->assertEquals($taxesTotals[1]->getId(), $taxesTotalsXml[1]->id);
    }

    public function test_it_converts_xml_to_invoice_taxes_totals()
    {
        $invoice = $this->getInvoiceFromXml();

        $taxesTotals = $invoice->getTaxesTotals();

        $this->assertCount(2, $taxesTotals);
        
        $this->assertEquals(1, $taxesTotals[0]->getTaxType());
        $this->assertEquals(4, $taxesTotals[0]->getTaxCategory());
        $this->assertEquals(5, $taxesTotals[0]->getTaxAmount());

        $this->assertEquals(1, $taxesTotals[1]->getTaxType());
        $this->assertEquals(3, $taxesTotals[1]->getTaxCategory());
        $this->assertEquals(4, $taxesTotals[1]->getUnderlyingValue());
        $this->assertEquals(0.8, $taxesTotals[1]->getTaxAmount());
        $this->assertEquals(21, $taxesTotals[1]->getId());
        
    }
}