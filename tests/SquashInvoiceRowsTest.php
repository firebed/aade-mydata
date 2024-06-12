<?php

namespace Tests;

use Firebed\AadeMyData\Enums\ExpenseClassificationCategory;
use Firebed\AadeMyData\Enums\ExpenseClassificationType;
use Firebed\AadeMyData\Enums\FeesPercentCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Enums\OtherTaxesPercentCategory;
use Firebed\AadeMyData\Enums\RecType;
use Firebed\AadeMyData\Enums\StampCategory;
use Firebed\AadeMyData\Enums\VatCategory;
use Firebed\AadeMyData\Enums\VatExemption;
use Firebed\AadeMyData\Enums\WithheldPercentCategory;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoiceDetails;
use PHPUnit\Framework\TestCase;

class SquashInvoiceRowsTest extends TestCase
{
    public function test_same_vat_category_rows_are_squashed()
    {
        $invoice = new Invoice();

        $invoice->addInvoiceDetails(new InvoiceDetails([
            'vatCategory' => VatCategory::VAT_1,
            'netValue' => 4.03,
            'vatAmount' => 0.97,
            'incomeClassification' => [
                [
                    'classificationCategory' => IncomeClassificationCategory::CATEGORY_1_1,
                    'classificationType' => IncomeClassificationType::E3_561_001,
                    'amount' => 4.03,
                ]
            ]
        ]));

        $invoice->addInvoiceDetails(new InvoiceDetails([
            'vatCategory' => VatCategory::VAT_1,
            'netValue' => 4.03,
            'vatAmount' => 0.97,
            'incomeClassification' => [
                [
                    'classificationCategory' => IncomeClassificationCategory::CATEGORY_1_1,
                    'classificationType' => IncomeClassificationType::E3_561_001,
                    'amount' => 2.02,
                ],
                [
                    'classificationCategory' => IncomeClassificationCategory::CATEGORY_1_1,
                    'classificationType' => IncomeClassificationType::E3_561_007,
                    'amount' => 2.01,
                ]
            ]
        ]));

        $invoice->addInvoiceDetails(new InvoiceDetails([
            'vatCategory' => VatCategory::VAT_1,
            'netValue' => 4.03,
            'vatAmount' => 0.97,
            'incomeClassification' => [
                [
                    'classificationCategory' => IncomeClassificationCategory::CATEGORY_1_1,
                    'classificationType' => IncomeClassificationType::E3_561_001,
                    'amount' => 2.02,
                ],
                [
                    'classificationCategory' => IncomeClassificationCategory::CATEGORY_1_1,
                    'classificationType' => IncomeClassificationType::E3_561_007,
                    'amount' => 2.01,
                ]
            ]
        ]));
        $invoice->squashInvoiceRows(['clsLineNumber' => true])->summarizeInvoice();
        
        $rows = $invoice->getInvoiceDetails();
        $this->assertNotNull($rows);
        $this->assertCount(1, $rows);
        $this->assertEquals(1, $rows[0]->getLineNumber());
        $this->assertEquals(VatCategory::VAT_1, $rows[0]->getVatCategory());
        $this->assertEquals(12.09, $rows[0]->getNetValue());
        $this->assertEquals(2.91, $rows[0]->getVatAmount());

        $icls = $rows[0]->getIncomeClassification();
        $this->assertIsArray($icls);
        $this->assertCount(2, $icls);

        $this->assertEquals(IncomeClassificationCategory::CATEGORY_1_1, $icls[0]->getClassificationCategory());
        $this->assertEquals(IncomeClassificationType::E3_561_001, $icls[0]->getClassificationType());
        $this->assertEquals(8.07, $rows[0]->getIncomeClassification()[0]->getAmount());
        $this->assertEquals(1, $rows[0]->getIncomeClassification()[0]->getId());

        $this->assertEquals(IncomeClassificationCategory::CATEGORY_1_1, $icls[1]->getClassificationCategory());
        $this->assertEquals(IncomeClassificationType::E3_561_007, $icls[1]->getClassificationType());
        $this->assertEquals(4.02, $rows[0]->getIncomeClassification()[1]->getAmount());
        $this->assertEquals(2, $rows[0]->getIncomeClassification()[1]->getId());
        
        $this->assertEquals(15, $invoice->getInvoiceSummary()->getTotalGrossValue());
        $this->assertEquals(8.07, $invoice->getInvoiceSummary()->getIncomeClassifications()[0]->getAmount());
        $this->assertEquals(4.02, $invoice->getInvoiceSummary()->getIncomeClassifications()[1]->getAmount());
    }
    
    public function test_same_vat_exemption_category_rows_are_squashed()
    {
        $invoice = new Invoice();

        $invoice->addInvoiceDetails(new InvoiceDetails([
            'vatCategory' => VatCategory::VAT_1,
            'vatExemptionCategory' => VatExemption::TYPE_5,
            'netValue' => 10,
            'vatAmount' => 0,
            'incomeClassification' => [
                [
                    'classificationCategory' => IncomeClassificationCategory::CATEGORY_1_1,
                    'classificationType' => IncomeClassificationType::E3_106,
                    'amount' => 10,
                ]
            ]
        ]));

        $invoice->addInvoiceDetails(new InvoiceDetails([
            'vatCategory' => VatCategory::VAT_1,
            'vatExemptionCategory' => VatExemption::TYPE_5,
            'netValue' => 10,
            'vatAmount' => 0,
            'incomeClassification' => [
                [
                    'classificationCategory' => IncomeClassificationCategory::CATEGORY_1_1,
                    'classificationType' => IncomeClassificationType::E3_106,
                    'amount' => 10,
                ]
            ]
        ]));
        
        $invoice->squashInvoiceRows();

        $rows = $invoice->getInvoiceDetails();
        $this->assertNotNull($rows);
        $this->assertCount(1, $rows);
        $this->assertEquals(1, $rows[0]->getLineNumber());
        $this->assertEquals(VatCategory::VAT_1, $rows[0]->getVatCategory());
        $this->assertEquals(VatExemption::TYPE_5, $rows[0]->getVatExemptionCategory());
        $this->assertEquals(20, $rows[0]->getNetValue());
        $this->assertEquals(0, $rows[0]->getVatAmount());

        $this->assertIsArray($rows[0]->getIncomeClassification());
        $this->assertCount(1, $rows[0]->getIncomeClassification());
        $this->assertEquals(IncomeClassificationCategory::CATEGORY_1_1, $rows[0]->getIncomeClassification()[0]->getClassificationCategory());
        $this->assertEquals(IncomeClassificationType::E3_106, $rows[0]->getIncomeClassification()[0]->getClassificationType());
        $this->assertEquals(20, $rows[0]->getIncomeClassification()[0]->getAmount());
    }

    public function test_same_vat_category_with_income_and_expense_classifications()
    {
        $invoice = new Invoice();

        $invoice->addInvoiceDetails(new InvoiceDetails([
            'vatCategory' => VatCategory::VAT_1,
            'netValue' => 10,
            'vatAmount' => 2.4,
            'incomeClassification' => [
                [
                    'classificationCategory' => IncomeClassificationCategory::CATEGORY_1_1,
                    'classificationType' => IncomeClassificationType::E3_106,
                    'amount' => 10,
                ]
            ]
        ]));

        $invoice->addInvoiceDetails(new InvoiceDetails([
            'vatCategory' => VatCategory::VAT_1,
            'netValue' => 10,
            'vatAmount' => 2.4,
            'expensesClassification' => [
                [
                    'classificationCategory' => ExpenseClassificationCategory::CATEGORY_2_1,
                    'classificationType' => ExpenseClassificationType::E3_101,
                    'amount' => 10,
                ]
            ]
        ]));
        
        $invoice->squashInvoiceRows();

        $rows = $invoice->getInvoiceDetails();
        $this->assertNotNull($rows);
        $this->assertCount(1, $rows);
        $this->assertEquals(1, $rows[0]->getLineNumber());
        $this->assertEquals(VatCategory::VAT_1, $rows[0]->getVatCategory());
        $this->assertEquals(20, $rows[0]->getNetValue());

        $this->assertIsArray($rows[0]->getIncomeClassification());
        $this->assertCount(1, $rows[0]->getIncomeClassification());
        $this->assertEquals(IncomeClassificationCategory::CATEGORY_1_1, $rows[0]->getIncomeClassification()[0]->getClassificationCategory());
        $this->assertEquals(IncomeClassificationType::E3_106, $rows[0]->getIncomeClassification()[0]->getClassificationType());
        $this->assertEquals(10, $rows[0]->getIncomeClassification()[0]->getAmount());

        $this->assertIsArray($rows[0]->getExpensesClassification());
        $this->assertCount(1, $rows[0]->getExpensesClassification());
        $this->assertEquals(ExpenseClassificationCategory::CATEGORY_2_1, $rows[0]->getExpensesClassification()[0]->getClassificationCategory());
        $this->assertEquals(ExpenseClassificationType::E3_101, $rows[0]->getExpensesClassification()[0]->getClassificationType());
        $this->assertEquals(10, $rows[0]->getExpensesClassification()[0]->getAmount());
    }

    public function test_same_mixed_vat_exemption_category_rows_are_squashed()
    {
        $invoice = new Invoice();

        $invoice->addInvoiceDetails(new InvoiceDetails([
            'vatCategory' => VatCategory::VAT_1,
            'vatExemptionCategory' => VatExemption::TYPE_5,
            'netValue' => 10,
            'vatAmount' => 0,
            'incomeClassification' => [
                [
                    'classificationCategory' => IncomeClassificationCategory::CATEGORY_1_1,
                    'classificationType' => IncomeClassificationType::E3_106,
                    'amount' => 10,
                ]
            ]
        ]));

        $invoice->addInvoiceDetails(new InvoiceDetails([
            'vatCategory' => VatCategory::VAT_1,
            'vatExemptionCategory' => VatExemption::TYPE_5,
            'netValue' => 10,
            'vatAmount' => 0,
            'incomeClassification' => [
                [
                    'classificationCategory' => IncomeClassificationCategory::CATEGORY_1_1,
                    'classificationType' => IncomeClassificationType::E3_106,
                    'amount' => 10,
                ]
            ]
        ]));

        $invoice->addInvoiceDetails(new InvoiceDetails([
            'vatCategory' => VatCategory::VAT_1,
            'vatExemptionCategory' => VatExemption::TYPE_4,
            'netValue' => 30,
            'vatAmount' => 0,
            'incomeClassification' => [
                [
                    'classificationCategory' => IncomeClassificationCategory::CATEGORY_1_1,
                    'classificationType' => IncomeClassificationType::E3_106,
                    'amount' => 30,
                ]
            ]
        ]));

        $invoice->squashInvoiceRows();

        $rows = $invoice->getInvoiceDetails();
        $this->assertNotNull($rows);
        $this->assertCount(2, $rows);

        $this->assertEquals(1, $rows[0]->getLineNumber());
        $this->assertEquals(VatCategory::VAT_1, $rows[0]->getVatCategory());
        $this->assertEquals(VatExemption::TYPE_5, $rows[0]->getVatExemptionCategory());
        $this->assertEquals(20, $rows[0]->getNetValue());
        $this->assertEquals(0, $rows[0]->getVatAmount());

        // Income classification for row 0
        $this->assertIsArray($rows[0]->getIncomeClassification());
        $this->assertCount(1, $rows[0]->getIncomeClassification());
        $this->assertEquals(IncomeClassificationCategory::CATEGORY_1_1, $rows[0]->getIncomeClassification()[0]->getClassificationCategory());
        $this->assertEquals(IncomeClassificationType::E3_106, $rows[0]->getIncomeClassification()[0]->getClassificationType());
        $this->assertEquals(20, $rows[0]->getIncomeClassification()[0]->getAmount());

        $this->assertEquals(2, $rows[1]->getLineNumber());
        $this->assertEquals(VatCategory::VAT_1, $rows[1]->getVatCategory());
        $this->assertEquals(VatExemption::TYPE_4, $rows[1]->getVatExemptionCategory());
        $this->assertEquals(30, $rows[1]->getNetValue());
        $this->assertEquals(0, $rows[1]->getVatAmount());

        // Income classification for row 1
        $this->assertNotNull($rows[1]->getIncomeClassification());
        $this->assertCount(1, $rows[1]->getIncomeClassification());
        $this->assertEquals(IncomeClassificationCategory::CATEGORY_1_1, $rows[1]->getIncomeClassification()[0]->getClassificationCategory());
        $this->assertEquals(IncomeClassificationType::E3_106, $rows[1]->getIncomeClassification()[0]->getClassificationType());
        $this->assertEquals(30, $rows[1]->getIncomeClassification()[0]->getAmount());
    }

    public function test_same_tax_category_rows_are_squashed()
    {
        $invoice = new Invoice();
        $invoice->addInvoiceDetails(new InvoiceDetails([
            'vatCategory' => VatCategory::VAT_1,
            'netValue' => 40,
            'vatAmount' => 9.60,
            'withheldPercentCategory' => WithheldPercentCategory::TAX_2, // 20%
            'withheldAmount' => 8,
            'feesPercentCategory' => FeesPercentCategory::TYPE_1, // 12%
            'feesAmount' => 4.8,
            'otherTaxesPercentCategory' => OtherTaxesPercentCategory::TAX_2, // 20%
            'otherTaxesAmount' => 8,
            'stampDutyPercentCategory' => StampCategory::TYPE_4, // ποσό
            'stampDutyAmount' => 0.5,
            'deductionsAmount' => 1.5,
        ]));

        $invoice->addInvoiceDetails(new InvoiceDetails([
            'vatCategory' => VatCategory::VAT_1,
            'netValue' => 10,
            'vatAmount' => 2.4,
            'withheldPercentCategory' => WithheldPercentCategory::TAX_2, // 20%
            'withheldAmount' => 2,
            'feesPercentCategory' => FeesPercentCategory::TYPE_1, // 12%
            'feesAmount' => 1.2,
            'otherTaxesPercentCategory' => OtherTaxesPercentCategory::TAX_2, // 20%
            'otherTaxesAmount' => 1.2,
            'stampDutyPercentCategory' => StampCategory::TYPE_4, // ποσό
            'stampDutyAmount' => 0.5,
            'deductionsAmount' => 1.5,
        ]));

        $invoice->addInvoiceDetails(new InvoiceDetails([
            'vatCategory' => VatCategory::VAT_1,
            'netValue' => 50,
            'vatAmount' => 12,
            'withheldPercentCategory' => WithheldPercentCategory::TAX_2, // 20%
            'withheldAmount' => 10,
            'feesPercentCategory' => FeesPercentCategory::TYPE_1, // 12%
            'feesAmount' => 6,
            'otherTaxesPercentCategory' => OtherTaxesPercentCategory::TAX_2, // 20%
            'otherTaxesAmount' => 10,
            'stampDutyPercentCategory' => StampCategory::TYPE_4, // ποσό
            'stampDutyAmount' => 0.5,
            'deductionsAmount' => 1.5,
        ]));

        $invoice->squashInvoiceRows();

        $rows = $invoice->getInvoiceDetails();
        $this->assertIsArray($rows);
        $this->assertEquals(100, $rows[0]->getNetValue());
        $this->assertEquals(1, $rows[0]->getLineNumber());
        $this->assertEquals(24, $rows[0]->getVatAmount());
        $this->assertEquals(20, $rows[0]->getWithheldAmount());
        $this->assertEquals(1.5, $rows[0]->getStampDutyAmount());
        $this->assertEquals(12, $rows[0]->getFeesAmount());
        $this->assertEquals(19.2, $rows[0]->getOtherTaxesAmount());
        $this->assertEquals(4.5, $rows[0]->getDeductionsAmount());
        $this->assertEquals(VatCategory::VAT_1, $rows[0]->getVatCategory());
        $this->assertEquals(WithheldPercentCategory::TAX_2, $rows[0]->getWithheldPercentCategory());
        $this->assertEquals(StampCategory::TYPE_4, $rows[0]->getStampDutyPercentCategory());
        $this->assertEquals(FeesPercentCategory::TYPE_1, $rows[0]->getFeesPercentCategory());
        $this->assertEquals(OtherTaxesPercentCategory::TAX_2, $rows[0]->getOtherTaxesPercentCategory());
    }

    public function test_mixed_same_tax_category_rows_are_squashed()
    {
        $invoice = new Invoice();
        $invoice->addInvoiceDetails(new InvoiceDetails([
            'vatCategory' => VatCategory::VAT_1,
            'netValue' => 40,
            'vatAmount' => 9.60,
            'withheldPercentCategory' => WithheldPercentCategory::TAX_3, // 20%
            'withheldAmount' => 8,
            'feesPercentCategory' => FeesPercentCategory::TYPE_1, // 12%
            'feesAmount' => 4.8,
            'otherTaxesPercentCategory' => OtherTaxesPercentCategory::TAX_2, // 20%
            'otherTaxesAmount' => 8,
            'stampDutyPercentCategory' => StampCategory::TYPE_4, // ποσό
            'stampDutyAmount' => 0.5,
            'deductionsAmount' => 1.5,
        ]));

        $invoice->addInvoiceDetails(new InvoiceDetails([
            'vatCategory' => VatCategory::VAT_1,
            'netValue' => 10,
            'vatAmount' => 2.4,
            'withheldPercentCategory' => WithheldPercentCategory::TAX_3, // 20%
            'withheldAmount' => 2,
            'feesPercentCategory' => FeesPercentCategory::TYPE_1, // 12%
            'feesAmount' => 1.2,
            'otherTaxesPercentCategory' => OtherTaxesPercentCategory::TAX_2, // 20%
            'otherTaxesAmount' => 1.2,
            'stampDutyPercentCategory' => StampCategory::TYPE_4, // ποσό
            'stampDutyAmount' => 0.5,
            'deductionsAmount' => 1.5,
        ]));

        $invoice->addInvoiceDetails(new InvoiceDetails([
            'vatCategory' => VatCategory::VAT_1,
            'netValue' => 50,
            'vatAmount' => 12,
            'withheldPercentCategory' => WithheldPercentCategory::TAX_2, // 20%
            'withheldAmount' => 10,
            'feesPercentCategory' => FeesPercentCategory::TYPE_1, // 12%
            'feesAmount' => 6,
            'otherTaxesPercentCategory' => OtherTaxesPercentCategory::TAX_2, // 20%
            'otherTaxesAmount' => 10,
            'stampDutyPercentCategory' => StampCategory::TYPE_4, // ποσό
            'stampDutyAmount' => 0.5,
            'deductionsAmount' => 1.5,
        ]));

        $invoice->squashInvoiceRows();

        $rows = $invoice->getInvoiceDetails();
        $this->assertIsArray($rows);
        $this->assertCount(2, $rows);

        $this->assertEquals(1, $rows[0]->getLineNumber());
        $this->assertEquals(50, $rows[0]->getNetValue());
        $this->assertEquals(12, $rows[0]->getVatAmount());
        $this->assertEquals(10, $rows[0]->getWithheldAmount());
        $this->assertEquals(1, $rows[0]->getStampDutyAmount());
        $this->assertEquals(6, $rows[0]->getFeesAmount());
        $this->assertEquals(9.2, $rows[0]->getOtherTaxesAmount());
        $this->assertEquals(3, $rows[0]->getDeductionsAmount());
        $this->assertEquals(VatCategory::VAT_1, $rows[0]->getVatCategory());
        $this->assertEquals(WithheldPercentCategory::TAX_3, $rows[0]->getWithheldPercentCategory());
        $this->assertEquals(StampCategory::TYPE_4, $rows[0]->getStampDutyPercentCategory());
        $this->assertEquals(FeesPercentCategory::TYPE_1, $rows[0]->getFeesPercentCategory());
        $this->assertEquals(OtherTaxesPercentCategory::TAX_2, $rows[0]->getOtherTaxesPercentCategory());

        $this->assertEquals(2, $rows[1]->getLineNumber());
        $this->assertEquals(50, $rows[1]->getNetValue());
        $this->assertEquals(12, $rows[1]->getVatAmount());
        $this->assertEquals(10, $rows[1]->getWithheldAmount());
        $this->assertEquals(0.5, $rows[1]->getStampDutyAmount());
        $this->assertEquals(6, $rows[1]->getFeesAmount());
        $this->assertEquals(10, $rows[1]->getOtherTaxesAmount());
        $this->assertEquals(1.5, $rows[1]->getDeductionsAmount());
        $this->assertEquals(VatCategory::VAT_1, $rows[1]->getVatCategory());
        $this->assertEquals(WithheldPercentCategory::TAX_2, $rows[1]->getWithheldPercentCategory());
        $this->assertEquals(StampCategory::TYPE_4, $rows[1]->getStampDutyPercentCategory());
        $this->assertEquals(FeesPercentCategory::TYPE_1, $rows[1]->getFeesPercentCategory());
        $this->assertEquals(OtherTaxesPercentCategory::TAX_2, $rows[1]->getOtherTaxesPercentCategory());
    }

    public function test_rows_with_rec_type_are_not_squashed()
    {
        $invoice = new Invoice();
        $invoice->setInvoiceDetails(InvoiceDetails::factory(4)->make([
            'recType' => null,
            'withheldPercentCategory' => null,
            'stampDutyPercentCategory' => null,
            'otherTaxesPercentCategory' => null,
            'feesPercentCategory' => null,
        ]));

        for ($i = 0; $i < 5; $i++) {
            $invoice->addInvoiceDetails(InvoiceDetails::factory()->make([
                'recType' => RecType::TYPE_5,
            ]));
        }

        $invoice->squashInvoiceRows();
        $rows = $invoice->getInvoiceDetails();
        
        $this->assertIsArray($rows);
        $this->assertCount(6, $rows);
        $this->assertEquals(1, $rows[0]->getLineNumber());

        $this->assertCount(5, array_filter($invoice->getInvoiceDetails(), fn($row) => $row->getRecType() === RecType::TYPE_5));
        $this->assertEquals(2, $rows[1]->getLineNumber());
        $this->assertEquals(3, $rows[2]->getLineNumber());
        $this->assertEquals(4, $rows[3]->getLineNumber());
        $this->assertEquals(5, $rows[4]->getLineNumber());
        $this->assertEquals(6, $rows[5]->getLineNumber());
    }
}