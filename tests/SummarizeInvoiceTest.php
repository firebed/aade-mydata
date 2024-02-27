<?php

namespace Tests;

use Firebed\AadeMyData\Enums\ExpenseClassificationCategory;
use Firebed\AadeMyData\Enums\ExpenseClassificationType;
use Firebed\AadeMyData\Enums\FeesPercentCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Enums\OtherTaxesPercentCategory;
use Firebed\AadeMyData\Enums\StampCategory;
use Firebed\AadeMyData\Enums\TaxType;
use Firebed\AadeMyData\Enums\VatCategory;
use Firebed\AadeMyData\Enums\VatExemption;
use Firebed\AadeMyData\Enums\WithheldPercentCategory;
use Firebed\AadeMyData\Models\ExpensesClassification;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoiceDetails;
use Firebed\AadeMyData\Models\TaxTotals;
use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;

class SummarizeInvoiceTest extends TestCase
{
    use HandlesInvoiceXml;

    public function test_it_summarizes_invoice_rows()
    {
        $row1 = new InvoiceDetails();
        $row1->setNetValue(100);
        $row1->setVatAmount(24);
        $row1->setWithheldAmount(12.3);
        $row1->setFeesAmount(8.55);

        $row2 = new InvoiceDetails();
        $row2->setNetValue(25);
        $row2->setVatAmount(6);
        $row2->setWithheldAmount(13.45);
        $row2->setDeductionsAmount(10.11);

        $row3 = new InvoiceDetails();
        $row3->setNetValue(30);
        $row3->setVatAmount(7.2);
        $row3->setOtherTaxesAmount(20);
        $row3->setFeesAmount(5.6);
        $row3->setStampDutyAmount(6.26);
        $row3->setDeductionsAmount(2.15);

        $invoice = new Invoice();
        $invoice->addInvoiceDetails($row1);
        $invoice->addInvoiceDetails($row2);
        $invoice->addInvoiceDetails($row3);

        $summary = $invoice->summarizeInvoice();

        $this->assertEquals(155, $summary->getTotalNetValue());
        $this->assertEquals(37.2, $summary->getTotalVatAmount());
        $this->assertEquals(25.75, $summary->getTotalWithheldAmount());
        $this->assertEquals(20, $summary->getTotalOtherTaxesAmount());
        $this->assertEquals(12.26, $summary->getTotalDeductionsAmount());
        $this->assertEquals(14.15, $summary->getTotalFeesAmount());
        $this->assertEquals(6.26, $summary->getTotalStampDutyAmount());
        $this->assertEquals(113.78, $summary->getTotalGrossValue());
    }

    public function test_it_summarizes_invoice_row_income_classifications()
    {
        $row1 = new InvoiceDetails();
        $row1->addIncomeClassification(IncomeClassificationType::E3_561_001, IncomeClassificationCategory::CATEGORY_1_2, 100.55);

        $row2 = new InvoiceDetails();
        $row2->addIncomeClassification(IncomeClassificationType::E3_561_001, IncomeClassificationCategory::CATEGORY_1_2, 200.55);

        $row3 = new InvoiceDetails();
        $row3->addIncomeClassification(IncomeClassificationType::E3_561_001, IncomeClassificationCategory::CATEGORY_1_3, 300.60);

        $row4 = new InvoiceDetails();
        $row4->addIncomeClassification(IncomeClassificationType::E3_561_001, IncomeClassificationCategory::CATEGORY_1_3, 400.20);

        $row5 = new InvoiceDetails();
        $row5->addIncomeClassification(IncomeClassificationType::E3_561_002, IncomeClassificationCategory::CATEGORY_1_2, 500.88);

        $row6 = new InvoiceDetails();
        $row6->addIncomeClassification(IncomeClassificationType::E3_561_002, IncomeClassificationCategory::CATEGORY_1_3, 600.66);

        $row7 = new InvoiceDetails();
        $row7->addIncomeClassification(IncomeClassificationType::E3_561_005, IncomeClassificationCategory::CATEGORY_1_6, 600);

        $row8 = new InvoiceDetails();
        $row8->addIncomeClassification(null, IncomeClassificationCategory::CATEGORY_1_6, 20.55);

        $row9 = new InvoiceDetails();
        $row9->addIncomeClassification(null, IncomeClassificationCategory::CATEGORY_1_6, 40.45);

        $row10 = new InvoiceDetails();
        $row10->addIncomeClassification(null, IncomeClassificationCategory::CATEGORY_1_7, 115.66);

        $invoice = new Invoice();
        for ($i = 1; $i <= 10; $i++) {
            $invoice->addInvoiceDetails(${"row$i"});
        }

        $summary = $invoice->summarizeInvoice();
        $icls = $summary->getIncomeClassifications();

        $this->assertCount(7, $icls);

        $icls1 = $this->findIncomeClassification($icls, IncomeClassificationType::E3_561_001, IncomeClassificationCategory::CATEGORY_1_2);
        $this->assertNotNull($icls1);
        $this->assertEquals(301.10, $icls1->getAmount());

        $icls2 = $this->findIncomeClassification($icls, IncomeClassificationType::E3_561_001, IncomeClassificationCategory::CATEGORY_1_3);
        $this->assertNotNull($icls2);
        $this->assertEquals(700.80, $icls2->getAmount());

        $icls3 = $this->findIncomeClassification($icls, IncomeClassificationType::E3_561_002, IncomeClassificationCategory::CATEGORY_1_2);
        $this->assertNotNull($icls3);
        $this->assertEquals(500.88, $icls3->getAmount());

        $icls4 = $this->findIncomeClassification($icls, IncomeClassificationType::E3_561_002, IncomeClassificationCategory::CATEGORY_1_3);
        $this->assertNotNull($icls4);
        $this->assertEquals(600.66, $icls4->getAmount());

        $icls5 = $this->findIncomeClassification($icls, IncomeClassificationType::E3_561_005, IncomeClassificationCategory::CATEGORY_1_6);
        $this->assertNotNull($icls5);
        $this->assertEquals(600, $icls5->getAmount());

        $icls6 = $this->findIncomeClassification($icls, null, IncomeClassificationCategory::CATEGORY_1_6);
        $this->assertNotNull($icls6);
        $this->assertEquals(61, $icls6->getAmount());

        $icls7 = $this->findIncomeClassification($icls, null, IncomeClassificationCategory::CATEGORY_1_7);
        $this->assertNotNull($icls7);
        $this->assertEquals(115.66, $icls7->getAmount());
    }

    public function test_it_summarizes_invoice_row_expenses_classifications()
    {
        $row1 = new InvoiceDetails();
        $row1->addExpensesClassification($this->createEcls(ExpenseClassificationType::E3_102_001, ExpenseClassificationCategory::CATEGORY_2_1, 100.55));

        $row2 = new InvoiceDetails();
        $row2->addExpensesClassification($this->createEcls(ExpenseClassificationType::E3_102_001, ExpenseClassificationCategory::CATEGORY_2_1, 200.55));

        $row3 = new InvoiceDetails();
        $row3->addExpensesClassification($this->createEcls(ExpenseClassificationType::E3_102_001, ExpenseClassificationCategory::CATEGORY_2_3, 300.60));

        $row4 = new InvoiceDetails();
        $row4->addExpensesClassification($this->createEcls(ExpenseClassificationType::E3_102_001, ExpenseClassificationCategory::CATEGORY_2_3, 400.20));

        $row5 = new InvoiceDetails();
        $row5->addExpensesClassification($this->createEcls(ExpenseClassificationType::E3_102_002, ExpenseClassificationCategory::CATEGORY_2_1, 500.88));

        $row6 = new InvoiceDetails();
        $row6->addExpensesClassification($this->createEcls(ExpenseClassificationType::E3_102_002, ExpenseClassificationCategory::CATEGORY_2_3, 600.66));

        $row7 = new InvoiceDetails();
        $row7->addExpensesClassification($this->createEcls(ExpenseClassificationType::E3_102_006, ExpenseClassificationCategory::CATEGORY_2_6, 600));

        $row8 = new InvoiceDetails();
        $row8->addExpensesClassification($this->createEcls(null, ExpenseClassificationCategory::CATEGORY_2_6, 20.55));

        $row9 = new InvoiceDetails();
        $row9->addExpensesClassification($this->createEcls(null, ExpenseClassificationCategory::CATEGORY_2_6, 40.45));

        $row10 = new InvoiceDetails();
        $row10->addExpensesClassification($this->createEcls(null, ExpenseClassificationCategory::CATEGORY_2_7, 115.66));

        $row11 = new InvoiceDetails();
        $row11->addExpensesClassification($this->createEcls(null, ExpenseClassificationCategory::CATEGORY_2_7, 50, VatCategory::VAT_1, 12));
        
        $row12 = new InvoiceDetails();
        $row12->addExpensesClassification($this->createEcls(null, ExpenseClassificationCategory::CATEGORY_2_7, 40, VatCategory::VAT_1, 9.60));

        $row13 = new InvoiceDetails();
        $row13->addExpensesClassification($this->createEcls(null, ExpenseClassificationCategory::CATEGORY_2_7, 50, VatCategory::VAT_7, 0, VatExemption::TYPE_12));

        $row14 = new InvoiceDetails();
        $row14->addExpensesClassification($this->createEcls(ExpenseClassificationType::E3_102_006, ExpenseClassificationCategory::CATEGORY_2_7, 150, VatCategory::VAT_7, 0, VatExemption::TYPE_13));

        $row15 = new InvoiceDetails();
        $row15->addExpensesClassification($this->createEcls(ExpenseClassificationType::E3_102_006, ExpenseClassificationCategory::CATEGORY_2_7, 10, VatCategory::VAT_7, 0, VatExemption::TYPE_13));
        
        $invoice = new Invoice();
        for ($i = 1; $i <= 15; $i++) {
            $invoice->addInvoiceDetails(${"row$i"});
        }

        $summary = $invoice->summarizeInvoice();
        $ecls = $summary->getExpensesClassifications();

        $this->assertCount(10, $ecls);
        
        $ecls1 = $this->findExpensesClassification($ecls, ExpenseClassificationType::E3_102_001, ExpenseClassificationCategory::CATEGORY_2_1);
        $this->assertNotNull($ecls1);
        $this->assertEquals(301.10, $ecls1->getAmount());

        $ecls2 = $this->findExpensesClassification($ecls, ExpenseClassificationType::E3_102_001, ExpenseClassificationCategory::CATEGORY_2_3);
        $this->assertNotNull($ecls2);
        $this->assertEquals(700.80, $ecls2->getAmount());

        $ecls3 = $this->findExpensesClassification($ecls, ExpenseClassificationType::E3_102_002, ExpenseClassificationCategory::CATEGORY_2_1);
        $this->assertNotNull($ecls3);
        $this->assertEquals(500.88, $ecls3->getAmount());

        $ecls4 = $this->findExpensesClassification($ecls, ExpenseClassificationType::E3_102_002, ExpenseClassificationCategory::CATEGORY_2_3);
        $this->assertNotNull($ecls4);
        $this->assertEquals(600.66, $ecls4->getAmount());

        $ecls5 = $this->findExpensesClassification($ecls, ExpenseClassificationType::E3_102_006, ExpenseClassificationCategory::CATEGORY_2_6);
        $this->assertNotNull($ecls5);
        $this->assertEquals(600, $ecls5->getAmount());

        $ecls6 = $this->findExpensesClassification($ecls, null, ExpenseClassificationCategory::CATEGORY_2_6);
        $this->assertNotNull($ecls6);
        $this->assertEquals(61, $ecls6->getAmount());

        $ecls7 = $this->findExpensesClassification($ecls, null, ExpenseClassificationCategory::CATEGORY_2_7);
        $this->assertNotNull($ecls7);
        $this->assertEquals(115.66, $ecls7->getAmount());

        $ecls8 = $this->findExpensesClassification($ecls, null, ExpenseClassificationCategory::CATEGORY_2_7, VatCategory::VAT_1);
        $this->assertNotNull($ecls8);
        $this->assertEquals(90, $ecls8->getAmount());
        $this->assertEquals(21.6, $ecls8->getVatAmount());

        $ecls10 = $this->findExpensesClassification($ecls, null, ExpenseClassificationCategory::CATEGORY_2_7, VatCategory::VAT_7, VatExemption::TYPE_12);
        $this->assertNotNull($ecls10);
        $this->assertEquals(50, $ecls10->getAmount());
        $this->assertEquals(0, $ecls10->getVatAmount());

        $ecls10 = $this->findExpensesClassification($ecls, ExpenseClassificationType::E3_102_006, ExpenseClassificationCategory::CATEGORY_2_7, VatCategory::VAT_7, VatExemption::TYPE_13);
        $this->assertNotNull($ecls10);
        $this->assertEquals(160, $ecls10->getAmount());
        $this->assertEquals(0, $ecls10->getVatAmount());
    }
    
    public function test_it_summarizes_invoice_taxes()
    {
        $row = new InvoiceDetails();
        $row->setNetValue(15000);
        $row->setVatAmount(3600);

        $tax1 = new TaxTotals();
        $tax1->setTaxAmount(100);
        $tax1->setTaxType(TaxType::TYPE_1);
        $tax1->setTaxCategory(WithheldPercentCategory::TAX_1);

        $tax2 = new TaxTotals();
        $tax2->setTaxAmount(25);
        $tax2->setTaxType(TaxType::TYPE_2);
        $tax2->setTaxCategory(FeesPercentCategory::TYPE_18);

        $tax3 = new TaxTotals();
        $tax3->setTaxAmount(36.55);
        $tax3->setTaxType(TaxType::TYPE_3);
        $tax3->setTaxCategory(OtherTaxesPercentCategory::TAX_13);

        $tax4 = new TaxTotals();
        $tax4->setTaxAmount(155.44);
        $tax4->setTaxType(TaxType::TYPE_4);
        $tax4->setTaxCategory(StampCategory::TYPE_2);

        $tax5 = new TaxTotals();
        $tax5->setTaxAmount(1620.36);
        $tax5->setTaxType(TaxType::TYPE_5);

        $tax6 = new TaxTotals();
        $tax6->setTaxAmount(544.12);
        $tax6->setTaxType(TaxType::TYPE_4);
        $tax6->setTaxCategory(StampCategory::TYPE_1);

        $tax7 = new TaxTotals();
        $tax7->setTaxAmount(665.88);
        $tax7->setTaxType(TaxType::TYPE_3);
        $tax7->setTaxCategory(OtherTaxesPercentCategory::TAX_6);

        $tax8 = new TaxTotals();
        $tax8->setTaxAmount(350.66);
        $tax8->setTaxType(TaxType::TYPE_1);
        $tax8->setTaxCategory(WithheldPercentCategory::TAX_11);

        $invoice = new Invoice();
        $invoice->addInvoiceDetails($row);
        $invoice->addTaxesTotals($tax1);
        $invoice->addTaxesTotals($tax2);
        $invoice->addTaxesTotals($tax3);
        $invoice->addTaxesTotals($tax4);
        $invoice->addTaxesTotals($tax5);
        $invoice->addTaxesTotals($tax6);
        $invoice->addTaxesTotals($tax7);
        $invoice->addTaxesTotals($tax8);

        $summary = $invoice->summarizeInvoice();

        $this->assertEquals(15000, $summary->getTotalNetValue());
        $this->assertEquals(3600, $summary->getTotalVatAmount());
        $this->assertEquals(450.66, $summary->getTotalWithheldAmount());
        $this->assertEquals(702.43, $summary->getTotalOtherTaxesAmount());
        $this->assertEquals(1620.36, $summary->getTotalDeductionsAmount());
        $this->assertEquals(25, $summary->getTotalFeesAmount());
        $this->assertEquals(699.56, $summary->getTotalStampDutyAmount());
        $this->assertEquals(15_101.99, $summary->getTotalGrossValue());
    }

    private function createEcls($type, $category, float $amount, $vat = null, $vatAmount = null, $exemption = null): ExpensesClassification
    {
        $ecls = new ExpensesClassification();
        $ecls->setClassificationCategory($category);
        $ecls->setClassificationType($type);
        $ecls->setAmount($amount);
        $ecls->setVatCategory($vat);
        $ecls->setVatExemptionCategory($exemption);
        $ecls->setVatAmount($vatAmount);
        return $ecls;
    }

    private function findIncomeClassification(array $source, $type, $category)
    {
        $type = $type->value ?? $type;
        $category = $category->value ?? $category;

        foreach ($source as $icls) {
            if (($icls->getClassificationCategory()->value ?? null) === $category && ($icls->getClassificationType()->value ?? null) === $type) {
                return $icls;
            }
        }
        return null;
    }

    private function findExpensesClassification(array $source, $type, $category = null, $vat = null, $exemption = null)
    {
        $type = $type->value ?? $type;
        $category = $category->value ?? $category;
        $vat = $vat->value ?? $vat;
        $exemption = $exemption->value ?? $exemption;

        foreach ($source as $ecls) {
            if (
                ($ecls->getClassificationCategory()->value ?? null) === $category 
                && ($ecls->getClassificationType()->value ?? null) === $type
                && ($ecls->getVatCategory()->value ?? null) === $vat
                && ($ecls->getVatExemptionCategory()->value ?? null) === $exemption
            ) {
                return $ecls;
            }
        }
        return null;
    }
}