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
        $row1 = (new InvoiceDetails())
            ->setNetValue(100.456)
            ->setVatAmount(24.1094)
            ->setWithheldAmount(12.3)
            ->setFeesAmount(8.55);

        $row2 = (new InvoiceDetails())
            ->setNetValue(25.123)
            ->setVatAmount(6.0295)
            ->setWithheldAmount(13.45)
            ->setDeductionsAmount(10.11);

        $row3 = (new InvoiceDetails())
            ->setNetValue(30.2987)
            ->setVatAmount(7.2717)
            ->setOtherTaxesAmount(20)
            ->setFeesAmount(5.6)
            ->setStampDutyAmount(6.26)
            ->setDeductionsAmount(2.15);

        $invoice = (new Invoice())
            ->addInvoiceDetails($row1)
            ->addInvoiceDetails($row2)
            ->addInvoiceDetails($row3)
            ->squashInvoiceRows()
            ->summarizeInvoice();

        $summary = $invoice->getInvoiceSummary();

        $this->assertNotNull($summary);
        $this->assertEquals(155.88, $summary->getTotalNetValue());
        $this->assertEquals(37.41, $summary->getTotalVatAmount());
        $this->assertEquals(25.75, $summary->getTotalWithheldAmount());
        $this->assertEquals(20, $summary->getTotalOtherTaxesAmount());
        $this->assertEquals(12.26, $summary->getTotalDeductionsAmount());
        $this->assertEquals(14.15, $summary->getTotalFeesAmount());
        $this->assertEquals(6.26, $summary->getTotalStampDutyAmount());
        $this->assertEquals(195.69, $summary->getTotalGrossValue());
    }

    public function test_it_summarizes_invoice_row_income_classifications()
    {
        $icls = [
            [IncomeClassificationType::E3_561_001, IncomeClassificationCategory::CATEGORY_1_2, 100.55],
            [IncomeClassificationType::E3_561_001, IncomeClassificationCategory::CATEGORY_1_2, 200.55],
            [IncomeClassificationType::E3_561_001, IncomeClassificationCategory::CATEGORY_1_3, 300.60],
            [IncomeClassificationType::E3_561_001, IncomeClassificationCategory::CATEGORY_1_3, 400.20],
            [IncomeClassificationType::E3_561_002, IncomeClassificationCategory::CATEGORY_1_2, 500.88],
            [IncomeClassificationType::E3_561_002, IncomeClassificationCategory::CATEGORY_1_3, 600.66],
            [IncomeClassificationType::E3_561_005, IncomeClassificationCategory::CATEGORY_1_6, 600],
            [null, IncomeClassificationCategory::CATEGORY_1_6, 20.55],
            [null, IncomeClassificationCategory::CATEGORY_1_6, 40.45],
            [null, IncomeClassificationCategory::CATEGORY_1_7, 115.66],
        ];

        $invoice = new Invoice();
        foreach ($icls as $cls) {
            $row = new InvoiceDetails();
            $row->addIncomeClassification(...$cls);
            $invoice->addInvoiceDetails($row);
        }

        $invoice->squashInvoiceRows()->summarizeInvoice();

        $icls = $invoice->getInvoiceSummary()->getIncomeClassifications();
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
        $ecls = [
            $this->createEcls(ExpenseClassificationType::E3_102_001, ExpenseClassificationCategory::CATEGORY_2_1, 100.55),
            $this->createEcls(ExpenseClassificationType::E3_102_001, ExpenseClassificationCategory::CATEGORY_2_1, 200.55),
            $this->createEcls(ExpenseClassificationType::E3_102_001, ExpenseClassificationCategory::CATEGORY_2_3, 300.60),
            $this->createEcls(ExpenseClassificationType::E3_102_001, ExpenseClassificationCategory::CATEGORY_2_3, 400.20),
            $this->createEcls(ExpenseClassificationType::E3_102_002, ExpenseClassificationCategory::CATEGORY_2_1, 500.88),
            $this->createEcls(ExpenseClassificationType::E3_102_002, ExpenseClassificationCategory::CATEGORY_2_3, 600.66),
            $this->createEcls(ExpenseClassificationType::E3_102_006, ExpenseClassificationCategory::CATEGORY_2_6, 600),
            $this->createEcls(null, ExpenseClassificationCategory::CATEGORY_2_6, 20.55),
            $this->createEcls(null, ExpenseClassificationCategory::CATEGORY_2_6, 40.45),
            $this->createEcls(null, ExpenseClassificationCategory::CATEGORY_2_7, 115.66),
            $this->createEcls(null, ExpenseClassificationCategory::CATEGORY_2_7, 50, VatCategory::VAT_1, 12),
            $this->createEcls(null, ExpenseClassificationCategory::CATEGORY_2_7, 40, VatCategory::VAT_1, 9.60),
            $this->createEcls(null, ExpenseClassificationCategory::CATEGORY_2_7, 50, VatCategory::VAT_7, 0, VatExemption::TYPE_12),
            $this->createEcls(ExpenseClassificationType::E3_102_006, ExpenseClassificationCategory::CATEGORY_2_7, 150, VatCategory::VAT_7, 0, VatExemption::TYPE_13),
            $this->createEcls(ExpenseClassificationType::E3_102_006, ExpenseClassificationCategory::CATEGORY_2_7, 10, VatCategory::VAT_7, 0, VatExemption::TYPE_13),
        ];

        $invoice = new Invoice();
        foreach ($ecls as $cls) {
            $row = new InvoiceDetails();
            $row->addExpensesClassification($cls);
            $invoice->addInvoiceDetails($row);
        }

        $invoice->squashInvoiceRows()->summarizeInvoice();

        $ecls = $invoice->getInvoiceSummary()->getExpensesClassifications();

        $this->assertCount(10, $ecls);

        $ecls1 = $this->findExpensesClassification($ecls, ExpenseClassificationType::E3_102_001, ExpenseClassificationCategory::CATEGORY_2_1);
        $this->assertNotNull($ecls1);
        $this->assertEquals(301.10, $ecls1->getAmount());
        $this->assertEmpty($ecls1->getId());

        $ecls2 = $this->findExpensesClassification($ecls, ExpenseClassificationType::E3_102_001, ExpenseClassificationCategory::CATEGORY_2_3);
        $this->assertNotNull($ecls2);
        $this->assertEquals(700.80, $ecls2->getAmount());
        $this->assertEmpty($ecls2->getId());

        $ecls3 = $this->findExpensesClassification($ecls, ExpenseClassificationType::E3_102_002, ExpenseClassificationCategory::CATEGORY_2_1);
        $this->assertNotNull($ecls3);
        $this->assertEquals(500.88, $ecls3->getAmount());
        $this->assertEmpty($ecls3->getId());

        $ecls4 = $this->findExpensesClassification($ecls, ExpenseClassificationType::E3_102_002, ExpenseClassificationCategory::CATEGORY_2_3);
        $this->assertNotNull($ecls4);
        $this->assertEquals(600.66, $ecls4->getAmount());
        $this->assertEmpty($ecls4->getId());

        $ecls5 = $this->findExpensesClassification($ecls, ExpenseClassificationType::E3_102_006, ExpenseClassificationCategory::CATEGORY_2_6);
        $this->assertNotNull($ecls5);
        $this->assertEquals(600, $ecls5->getAmount());
        $this->assertEmpty($ecls5->getId());

        $ecls6 = $this->findExpensesClassification($ecls, null, ExpenseClassificationCategory::CATEGORY_2_6);
        $this->assertNotNull($ecls6);
        $this->assertEquals(61, $ecls6->getAmount());
        $this->assertEmpty($ecls6->getId());

        $ecls7 = $this->findExpensesClassification($ecls, null, ExpenseClassificationCategory::CATEGORY_2_7);
        $this->assertNotNull($ecls7);
        $this->assertEquals(115.66, $ecls7->getAmount());
        $this->assertEmpty($ecls7->getId());

        $ecls8 = $this->findExpensesClassification($ecls, null, ExpenseClassificationCategory::CATEGORY_2_7, VatCategory::VAT_1);
        $this->assertNotNull($ecls8);
        $this->assertEquals(90, $ecls8->getAmount());
        $this->assertEquals(21.6, $ecls8->getVatAmount());
        $this->assertEmpty($ecls8->getId());

        $ecls9 = $this->findExpensesClassification($ecls, null, ExpenseClassificationCategory::CATEGORY_2_7, VatCategory::VAT_7, VatExemption::TYPE_12);
        $this->assertNotNull($ecls9);
        $this->assertEquals(50, $ecls9->getAmount());
        $this->assertEquals(0, $ecls9->getVatAmount());
        $this->assertEmpty($ecls9->getId());

        $ecls10 = $this->findExpensesClassification($ecls, ExpenseClassificationType::E3_102_006, ExpenseClassificationCategory::CATEGORY_2_7, VatCategory::VAT_7, VatExemption::TYPE_13);
        $this->assertNotNull($ecls10);
        $this->assertEquals(160, $ecls10->getAmount());
        $this->assertEquals(0, $ecls10->getVatAmount());
        $this->assertEmpty($ecls10->getId());
    }

    public function test_classification_ids_are_empty_when_disabled()
    {
        $row = new InvoiceDetails();
        $row->addExpensesClassification($this->createEcls(ExpenseClassificationType::E3_102_001, ExpenseClassificationCategory::CATEGORY_2_1, 100.55));

        $invoice = (new Invoice())
            ->addInvoiceDetails($row)
            ->squashInvoiceRows()
            ->summarizeInvoice(['enableClassificationIds' => true]);

        $ecls = $invoice->getInvoiceSummary()->getExpensesClassifications();

        $ecls1 = $this->findExpensesClassification($ecls, ExpenseClassificationType::E3_102_001, ExpenseClassificationCategory::CATEGORY_2_1);
        $this->assertNotNull($ecls1);
        $this->assertEquals(100.55, $ecls1->getAmount());
        $this->assertEquals(0, $ecls1->getVatAmount());
        $this->assertEquals(1, $ecls1->getId());
    }

    public function test_it_summarizes_invoice_taxes()
    {
        $row = (new InvoiceDetails())
            ->setNetValue(15_000)
            ->setVatAmount(3_600);

        $tax1 = (new TaxTotals())
            ->setTaxAmount(100)
            ->setTaxType(TaxType::TYPE_1)
            ->setTaxCategory(WithheldPercentCategory::TAX_1);

        $tax2 = (new TaxTotals())
            ->setTaxAmount(25)
            ->setTaxType(TaxType::TYPE_2)
            ->setTaxCategory(FeesPercentCategory::TYPE_18);

        $tax3 = (new TaxTotals())
            ->setTaxAmount(36.55)
            ->setTaxType(TaxType::TYPE_3)
            ->setTaxCategory(OtherTaxesPercentCategory::TAX_13);

        $tax4 = (new TaxTotals())
            ->setTaxAmount(155.44)
            ->setTaxType(TaxType::TYPE_4)
            ->setTaxCategory(StampCategory::TYPE_2);

        $tax5 = (new TaxTotals())
            ->setTaxAmount(1620.36)
            ->setTaxType(TaxType::TYPE_5);

        $tax6 = (new TaxTotals())
            ->setTaxAmount(544.12)
            ->setTaxType(TaxType::TYPE_4)
            ->setTaxCategory(StampCategory::TYPE_1);

        $tax7 = (new TaxTotals())
            ->setTaxAmount(665.88)
            ->setTaxType(TaxType::TYPE_3)
            ->setTaxCategory(OtherTaxesPercentCategory::TAX_6);

        $tax8 = (new TaxTotals())
            ->setTaxAmount(350.66)
            ->setTaxType(TaxType::TYPE_1)
            ->setTaxCategory(WithheldPercentCategory::TAX_11);

        $invoice = (new Invoice())
            ->addInvoiceDetails($row)
            ->addTaxesTotals($tax1)
            ->addTaxesTotals($tax2)
            ->addTaxesTotals($tax3)
            ->addTaxesTotals($tax4)
            ->addTaxesTotals($tax5)
            ->addTaxesTotals($tax6)
            ->addTaxesTotals($tax7)
            ->addTaxesTotals($tax8)
            ->squashInvoiceRows()
            ->summarizeInvoice();

        $summary = $invoice->getInvoiceSummary();

        $this->assertNotNull($summary);
        $this->assertEquals(15_000, $summary->getTotalNetValue());
        $this->assertEquals(3_600, $summary->getTotalVatAmount());
        $this->assertEquals(450.66, $summary->getTotalWithheldAmount());
        $this->assertEquals(702.43, $summary->getTotalOtherTaxesAmount());
        $this->assertEquals(1_620.36, $summary->getTotalDeductionsAmount());
        $this->assertEquals(25, $summary->getTotalFeesAmount());
        $this->assertEquals(699.56, $summary->getTotalStampDutyAmount());
        $this->assertEquals(17_955.97, $summary->getTotalGrossValue());
    }

    private function createEcls($type, $category, float $amount, $vat = null, $vatAmount = null, $exemption = null): ExpensesClassification
    {
        return (new ExpensesClassification())
            ->setClassificationCategory($category)
            ->setClassificationType($type)
            ->setAmount($amount)
            ->setVatCategory($vat)
            ->setVatExemptionCategory($exemption)
            ->setVatAmount($vatAmount);
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