<?php

namespace Tests;

use Firebed\AadeMyData\Models\ExpensesClassification;
use Firebed\AadeMyData\Models\IncomeClassification;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoiceSummary;
use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;

class InvoiceSummaryTest extends TestCase
{
    use HandlesInvoiceXml;

    public function test_it_converts_invoice_summary_with_single_classifications_to_xml(): void
    {
        $invoice = Invoice::factory()->make();

        $summary = $invoice->getInvoiceSummary();
        $summaryXml = $this->toXML($invoice)->InvoicesDoc->invoice->invoiceSummary;

        $this->assertCount(10, $summaryXml);
        $this->assertEquals($summary->getTotalNetValue(), $summaryXml->totalNetValue);
        $this->assertEquals($summary->getTotalVatAmount(), $summaryXml->totalVatAmount);
        $this->assertEquals($summary->getTotalWithheldAmount(), $summaryXml->totalWithheldAmount);
        $this->assertEquals($summary->getTotalFeesAmount(), $summaryXml->totalFeesAmount);
        $this->assertEquals($summary->getTotalStampDutyAmount(), $summaryXml->totalStampDutyAmount);
        $this->assertEquals($summary->getTotalOtherTaxesAmount(), $summaryXml->totalOtherTaxesAmount);
        $this->assertEquals($summary->getTotalDeductionsAmount(), $summaryXml->totalDeductionsAmount);
        $this->assertEquals($summary->getTotalGrossValue(), $summaryXml->totalGrossValue);

        $icls = $invoice->getInvoiceSummary()->getIncomeClassifications()[0];

        $this->assertEquals($icls->getClassificationType(), $summaryXml->incomeClassification->get('icls:classificationType'));
        $this->assertEquals($icls->getClassificationCategory(), $summaryXml->incomeClassification->get('icls:classificationCategory'));
        $this->assertEquals($icls->getAmount(), $summaryXml->incomeClassification->get('icls:amount'));
        $this->assertEquals($icls->getId(), $summaryXml->incomeClassification->get('icls:id'));

        $ecls = $invoice->getInvoiceSummary()->getExpensesClassifications()[0];
        $this->assertEquals($ecls->getClassificationType(), $summaryXml->expensesClassification->get('ecls:classificationType'));
        $this->assertEquals($ecls->getClassificationCategory(), $summaryXml->expensesClassification->get('ecls:classificationCategory'));
        $this->assertEquals($ecls->getAmount(), $summaryXml->expensesClassification->get('ecls:amount'));
        $this->assertEquals($ecls->getVatCategory(), $summaryXml->expensesClassification->get('ecls:vatCategory'));
        $this->assertEquals($ecls->getVatExemptionCategory(), $summaryXml->expensesClassification->get('ecls:vatExemptionCategory'));
        $this->assertEquals($ecls->getId(), $summaryXml->expensesClassification->get('ecls:id'));
    }

    public function test_it_converts_invoice_summary_with_multiple_classifications_to_xml(): void
    {
        $invoice = Invoice::factory()
            ->state([
                'invoiceSummary' => InvoiceSummary::factory()->state([
                    'incomeClassification'   => IncomeClassification::factory(4),
                    'expensesClassification' => ExpensesClassification::factory(6)
                ])
            ])->make();

        $summary = $invoice->getInvoiceSummary();
        $summaryXml = $this->toXML($invoice)->InvoicesDoc->invoice->invoiceSummary;

        $this->assertCount(10, $summaryXml);
        $this->assertCount(4, $summary->getIncomeClassifications());
        $this->assertCount(4, $summaryXml->incomeClassification);

        $this->assertCount(6, $summary->getExpensesClassifications());
        $this->assertCount(6, $summaryXml->expensesClassification);
    }

    public function test_it_converts_xml_to_invoice_summary()
    {
        $invoice = $this->getInvoiceFromXml();

        $summary = $invoice->getInvoiceSummary();

        $this->assertEquals(5000, $summary->getTotalNetValue());
        $this->assertEquals(1200, $summary->getTotalVatAmount());
        $this->assertEquals(100, $summary->getTotalWithheldAmount());
        $this->assertEquals(50, $summary->getTotalFeesAmount());
        $this->assertEquals(10, $summary->getTotalStampDutyAmount());
        $this->assertEquals(0, $summary->getTotalOtherTaxesAmount());
        $this->assertEquals(40, $summary->getTotalDeductionsAmount());
        $this->assertEquals(6000, $summary->getTotalGrossValue());
        
        $this->assertCount(1, $summary->getIncomeClassifications());

        $icls = $summary->getIncomeClassifications()[0];
        $this->assertEquals('E3_102_001', $icls->getClassificationType());
        $this->assertEquals('category2_1', $icls->getClassificationCategory());
        $this->assertEquals(5000, $icls->getAmount());
        $this->assertEquals(1, $icls->getId());

        $this->assertCount(2, $summary->getExpensesClassifications());
        $ecls1 = $summary->getExpensesClassifications()[0];
        $this->assertEquals('E3_102_001', $ecls1->getClassificationType());
        $this->assertEquals('category2_1', $ecls1->getClassificationCategory());
        $this->assertEquals(5000, $ecls1->getAmount());
        $this->assertEquals(4, $ecls1->getVatCategory());
        $this->assertEquals(12, $ecls1->getVatExemptionCategory());
        $this->assertEquals(2, $ecls1->getId());

        $ecls2 = $summary->getExpensesClassifications()[1];
        $this->assertEquals('VAT_361', $ecls2->getClassificationType());
        $this->assertEquals('category2_2', $ecls2->getClassificationCategory());
        $this->assertEquals(2000, $ecls2->getAmount());
        $this->assertEquals(3, $ecls2->getId());
    }
}
