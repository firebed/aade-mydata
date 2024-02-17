<?php

namespace Tests;

use Firebed\AadeMyData\Enums\ExpenseClassificationCategory;
use Firebed\AadeMyData\Enums\ExpenseClassificationType;
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoiceSummary;
use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;

class InvoiceSummaryTest extends TestCase
{
    use HandlesInvoiceXml;

    public function test_it_converts_invoice_summary_to_xml(): void
    {
        $summary = new InvoiceSummary();
        $summary->setTotalNetValue(500);
        $summary->setTotalVatAmount(100);
        $summary->setTotalWithheldAmount(5);
        $summary->setTotalFeesAmount(10);
        $summary->setTotalStampDutyAmount(15);
        $summary->setTotalOtherTaxesAmount(20);
        $summary->setTotalDeductionsAmount(25);
        $summary->setTotalGrossValue(525);
        $summary->addIncomeClassification(IncomeClassificationType::E3_561_001, IncomeClassificationCategory::CATEGORY_1_2, 400);
        $summary->addIncomeClassification(IncomeClassificationType::E3_561_002, IncomeClassificationCategory::CATEGORY_1_3, 75);
        $summary->addExpensesClassification(ExpenseClassificationType::E3_102_001, ExpenseClassificationCategory::CATEGORY_2_2, 100);
        $summary->addExpensesClassification(ExpenseClassificationType::E3_102_003, ExpenseClassificationCategory::CATEGORY_2_4, 50);

        $invoice = new Invoice();
        $invoice->setInvoiceSummary($summary);

        $xml = $this->toXML($invoice)->InvoicesDoc->invoice->invoiceSummary;

        $this->assertCount(10, $xml);
        $this->assertEquals(500, $xml->totalNetValue);
        $this->assertEquals(100, $xml->totalVatAmount);
        $this->assertEquals(5, $xml->totalWithheldAmount);
        $this->assertEquals(10, $xml->totalFeesAmount);
        $this->assertEquals(15, $xml->totalStampDutyAmount);
        $this->assertEquals(20, $xml->totalOtherTaxesAmount);
        $this->assertEquals(25, $xml->totalDeductionsAmount);
        $this->assertEquals(525, $xml->totalGrossValue);
        $this->assertIncomeClassification(IncomeClassificationType::E3_561_001, IncomeClassificationCategory::CATEGORY_1_2, 400, $xml->incomeClassification[0]);
        $this->assertIncomeClassification(IncomeClassificationType::E3_561_002, IncomeClassificationCategory::CATEGORY_1_3, 75, $xml->incomeClassification[1]);
        $this->assertExpensesClassification(ExpenseClassificationType::E3_102_001, ExpenseClassificationCategory::CATEGORY_2_2, 100, $xml->expensesClassification[0]);
        $this->assertExpensesClassification(ExpenseClassificationType::E3_102_003, ExpenseClassificationCategory::CATEGORY_2_4, 50, $xml->expensesClassification[1]);
    }
}
