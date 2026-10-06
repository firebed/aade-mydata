<?php

namespace Tests;

use Firebed\AadeMyData\Actions\SquashInvoiceRows;
use Firebed\AadeMyData\Enums\ExpenseClassificationCategory;
use Firebed\AadeMyData\Enums\ExpenseClassificationType;
use Firebed\AadeMyData\Enums\FeesPercentCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Enums\InvoiceDetailType;
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

    public function test_same_vat_category_with_income_and_expense_classifications_are_squashed()
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
        $invoice->addInvoiceDetails(new InvoiceDetails([
            'vatCategory' => VatCategory::VAT_1,
            'netValue' => 10,
            'recType' => null,
        ]));

        $invoice->addInvoiceDetails(new InvoiceDetails([
            'vatCategory' => VatCategory::VAT_1,
            'netValue' => 40,
            'recType' => null,
        ]));

        for ($i = 0; $i < 5; $i++) {
            $invoice->addInvoiceDetails(new InvoiceDetails([
                'vatCategory' => VatCategory::VAT_1,
                'netValue' => 10,
                'recType' => RecType::TYPE_5,
            ]));
        }

        $invoice->squashInvoiceRows();
        $rows = $invoice->getInvoiceDetails();

        $this->assertIsArray($rows);
        $this->assertCount(6, $rows);
        $this->assertCount(5, array_filter($invoice->getInvoiceDetails(), fn($row) => $row->getRecType() === RecType::TYPE_5));

        for ($i = 0; $i < count($rows); $i++) {
            $this->assertEquals($i + 1, $rows[$i]->getLineNumber());
        }
    }

    public function test_not_vat_195_rows_are_squashed()
    {
        $invoice = new Invoice();
        $invoice->addInvoiceDetails(new InvoiceDetails([
            'vatCategory' => VatCategory::VAT_1,
            'netValue' => 10,
        ]));

        $invoice->addInvoiceDetails(new InvoiceDetails([
            'vatCategory' => VatCategory::VAT_1,
            'netValue' => 40,
        ]));

        for ($i = 0; $i < 5; $i++) {
            $invoice->addInvoiceDetails(new InvoiceDetails([
                'vatCategory' => VatCategory::VAT_1,
                'netValue' => 10,
                'notVAT195' => true,
            ]));
        }

        $invoice->squashInvoiceRows();
        $rows = $invoice->getInvoiceDetails();

        $notVat195Rows = array_filter($invoice->getInvoiceDetails(), fn($row) => $row->getNotVAT195() === true);

        $this->assertIsArray($rows);
        $this->assertCount(2, $rows);
        $this->assertCount(1, $notVat195Rows);
        $this->assertEquals(50, array_reduce($notVat195Rows, fn($carry, $row) => $carry + $row->getNetValue(), 0));

        for ($i = 0; $i < count($rows); $i++) {
            $this->assertEquals($i + 1, $rows[$i]->getLineNumber());
        }
    }

    public function test_similar_invoice_detail_types_are_squashed_separately()
    {
        $invoice = new Invoice();
        $invoice->addInvoiceDetails(new InvoiceDetails([
            'vatCategory' => VatCategory::VAT_1,
            'netValue' => 10,
            'invoiceDetailType' => InvoiceDetailType::TYPE_1,
        ]));

        $invoice->addInvoiceDetails(new InvoiceDetails([
            'vatCategory' => VatCategory::VAT_1,
            'netValue' => 40,
            'invoiceDetailType' => InvoiceDetailType::TYPE_1,
        ]));

        $invoice->addInvoiceDetails(new InvoiceDetails([
            'vatCategory' => VatCategory::VAT_1,
            'netValue' => 10,
            'invoiceDetailType' => InvoiceDetailType::TYPE_2,
        ]));

        $invoice->addInvoiceDetails(new InvoiceDetails([
            'vatCategory' => VatCategory::VAT_1,
            'netValue' => 40,
            'invoiceDetailType' => InvoiceDetailType::TYPE_2,
        ]));

        $invoice->squashInvoiceRows();
        $rows = $invoice->getInvoiceDetails();

        $type1Rows = array_filter($invoice->getInvoiceDetails(), fn($row) => $row->getInvoiceDetailType() === InvoiceDetailType::TYPE_1);
        $type2Rows = array_filter($invoice->getInvoiceDetails(), fn($row) => $row->getInvoiceDetailType() === InvoiceDetailType::TYPE_2);

        $this->assertIsArray($rows);
        $this->assertCount(2, $rows);
        $this->assertEquals(50, array_reduce($type1Rows, fn($carry, $row) => $carry + $row->getNetValue(), 0));
        $this->assertEquals(50, array_reduce($type2Rows, fn($carry, $row) => $carry + $row->getNetValue(), 0));
        $this->assertEquals(50, $rows[0]->getNetValue());
        $this->assertEquals(50, $rows[1]->getNetValue());
    }

    public function test_similar_invoice_detail_types_are_not_squashed_when_vat_category_is_different()
    {
        $invoice = new Invoice();
        $invoice->addInvoiceDetails(new InvoiceDetails([
            'vatCategory' => VatCategory::VAT_1,
            'netValue' => 10,
            'invoiceDetailType' => InvoiceDetailType::TYPE_1,
        ]));

        $invoice->addInvoiceDetails(new InvoiceDetails([
            'vatCategory' => VatCategory::VAT_1,
            'netValue' => 40,
            'invoiceDetailType' => InvoiceDetailType::TYPE_1,
        ]));

        $invoice->addInvoiceDetails(new InvoiceDetails([
            'vatCategory' => VatCategory::VAT_1,
            'netValue' => 10,
            'invoiceDetailType' => InvoiceDetailType::TYPE_2,
        ]));

        $invoice->addInvoiceDetails(new InvoiceDetails([
            'vatCategory' => VatCategory::VAT_2,
            'netValue' => 40,
            'invoiceDetailType' => InvoiceDetailType::TYPE_2,
        ]));

        $invoice->squashInvoiceRows();
        $rows = $invoice->getInvoiceDetails();

        $type1Rows = array_filter($invoice->getInvoiceDetails(), fn($row) => $row->getInvoiceDetailType() === InvoiceDetailType::TYPE_1);
        $type2Rows = array_filter($invoice->getInvoiceDetails(), fn($row) => $row->getInvoiceDetailType() === InvoiceDetailType::TYPE_2);

        $this->assertIsArray($rows);
        $this->assertCount(3, $rows);
        $this->assertEquals(50, array_reduce($type1Rows, fn($carry, $row) => $carry + $row->getNetValue(), 0));
        $this->assertEquals(50, array_reduce($type2Rows, fn($carry, $row) => $carry + $row->getNetValue(), 0));
        $this->assertEquals(50, $rows[0]->getNetValue());
        $this->assertEquals(10, $rows[1]->getNetValue());
        $this->assertEquals(40, $rows[2]->getNetValue());
    }

    public function test_squash_revert(): void
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

        $invoice->squashInvoiceRows();
        $rows = $invoice->getInvoiceDetails();
        $this->assertNotNull($rows);
        $this->assertCount(1, $rows);
        $this->assertTrue($invoice->isSquashed());

        $invoice->unSquashInvoiceRows();
        $rows = $invoice->getInvoiceDetails();
        $this->assertNotNull($rows);
        $this->assertCount(3, $rows);
        $this->assertFalse($invoice->isSquashed());
    }


    public function test_expense_classification_with_vat_classification()
    {
        $invoice = new Invoice();
        $invoice->addInvoiceDetails(new InvoiceDetails([
            'vatCategory' => VatCategory::VAT_1,
            'netValue' => 10,
            'invoiceDetailType' => InvoiceDetailType::TYPE_1,
            'expensesClassification' => [
                [
                    'classificationCategory' => ExpenseClassificationCategory::CATEGORY_2_1,
                    'classificationType' => ExpenseClassificationType::E3_102_001,
                    'amount' => 10,
                ],
                [
                    'classificationType' => ExpenseClassificationType::VAT_361,
                    'amount' => 10,
                ]
            ]
        ]));

        $invoice->squashInvoiceRows();
        $rows = $invoice->getInvoiceDetails();

        $this->assertIsArray($rows);
        $this->assertCount(1, $rows);
        $this->assertEquals(10, $rows[0]->getNetValue());

        $this->assertIsArray($rows[0]->getExpensesClassification());
        $this->assertCount(2, $rows[0]->getExpensesClassification());

        $this->assertEquals(ExpenseClassificationCategory::CATEGORY_2_1, $rows[0]->getExpensesClassification()[0]->getClassificationCategory());
        $this->assertEquals(ExpenseClassificationType::E3_102_001, $rows[0]->getExpensesClassification()[0]->getClassificationType());
        $this->assertEquals(10, $rows[0]->getExpensesClassification()[0]->getAmount());

        $this->assertNull($rows[0]->getExpensesClassification()[1]->getClassificationCategory());
        $this->assertEquals(ExpenseClassificationType::VAT_361, $rows[0]->getExpensesClassification()[1]->getClassificationType());
        $this->assertEquals(10, $rows[0]->getExpensesClassification()[1]->getAmount());
    }

    public function test_rows_are_squashed_into_one_row_without_vat_amount_tolerance(): void
    {
        $invoice = $this->invoiceWithRows(array_fill(0, 11, [10, 2.50]));

        $invoice->squashInvoiceRows();
        $rows = $invoice->getInvoiceDetails();

        $this->assertCount(1, $rows);
        $this->assertEquals(110, $rows[0]->getNetValue());
        $this->assertEquals(27.50, $rows[0]->getVatAmount());
    }

    public function test_vat_amount_tolerance_starts_a_new_row_before_the_vat_amount_drifts_past_it(): void
    {
        $invoice = $this->invoiceWithRows(array_fill(0, 11, [10, 2.50]));

        $invoice->squashInvoiceRows(['vatAmountTolerance' => 1.00])->summarizeInvoice();
        $rows = $invoice->getInvoiceDetails();

        $this->assertCount(2, $rows);

        $this->assertEquals(1, $rows[0]->getLineNumber());
        $this->assertEquals(100, $rows[0]->getNetValue());
        $this->assertEquals(25.00, $rows[0]->getVatAmount());
        $this->assertEquals(100, $rows[0]->getIncomeClassification()[0]->getAmount());

        $this->assertEquals(2, $rows[1]->getLineNumber());
        $this->assertEquals(10, $rows[1]->getNetValue());
        $this->assertEquals(2.50, $rows[1]->getVatAmount());
        $this->assertEquals(10, $rows[1]->getIncomeClassification()[0]->getAmount());

        $this->assertEquals(110, $invoice->getInvoiceSummary()->getTotalNetValue());
        $this->assertEquals(27.50, $invoice->getInvoiceSummary()->getTotalVatAmount());
    }

    public function test_vat_amount_tolerance_keeps_a_row_already_past_it_on_its_own(): void
    {
        $invoice = $this->invoiceWithRows([[100, 24], [4.20, 0], [100, 24]]);

        $invoice->squashInvoiceRows(['vatAmountTolerance' => 1.00]);
        $rows = $invoice->getInvoiceDetails();

        $this->assertCount(2, $rows);
        $this->assertEquals(200, $rows[0]->getNetValue());
        $this->assertEquals(48, $rows[0]->getVatAmount());
        $this->assertEquals(4.20, $rows[1]->getNetValue());
        $this->assertEquals(0, $rows[1]->getVatAmount());
    }

    public function test_vat_amount_tolerance_is_checked_on_the_net_value_rounded_to_cents(): void
    {
        // 10.021 is sent as 10.02, whose 24% is 2.40, so 3.41 is 1.01 away although 10.021 * 24% rounds to 2.41.
        $invoice = $this->invoiceWithRows([[5, 1.70], [5.021, 1.71]]);

        $invoice->squashInvoiceRows(['vatAmountTolerance' => 1.00]);

        $this->assertCount(2, $invoice->getInvoiceDetails());
    }

    public function test_vat_amount_tolerance_rounds_the_expected_vat_half_to_even(): void
    {
        // 0.75 at 6% is 0.045, which myDATA rounds to 0.04, so 1.05 is 1.01 away.
        $invoice = $this->invoiceWithRows([[0.50, 0.53, VatCategory::VAT_3], [0.25, 0.52, VatCategory::VAT_3]]);

        $invoice->squashInvoiceRows(['vatAmountTolerance' => 1.00]);

        $this->assertCount(2, $invoice->getInvoiceDetails());
    }

    public function test_vat_amount_tolerance_keeps_classifications_with_their_split_row(): void
    {
        $invoice = new Invoice();

        for ($i = 0; $i < 11; $i++) {
            $invoice->addInvoiceDetails(new InvoiceDetails([
                'vatCategory' => VatCategory::VAT_1,
                'netValue' => 10,
                'vatAmount' => 2.50,
                'incomeClassification' => [
                    [
                        'classificationCategory' => IncomeClassificationCategory::CATEGORY_1_1,
                        'classificationType' => IncomeClassificationType::E3_561_001,
                        'amount' => 10,
                    ]
                ],
                'expensesClassification' => [
                    [
                        'classificationCategory' => ExpenseClassificationCategory::CATEGORY_2_1,
                        'classificationType' => ExpenseClassificationType::E3_101,
                        'amount' => 10,
                    ]
                ]
            ]));
        }

        $invoice->squashInvoiceRows(['vatAmountTolerance' => 1.00, 'clsLineNumber' => true]);
        $rows = $invoice->getInvoiceDetails();

        $this->assertCount(2, $rows);

        $this->assertCount(1, $rows[0]->getIncomeClassification());
        $this->assertEquals(100, $rows[0]->getIncomeClassification()[0]->getAmount());
        $this->assertEquals(1, $rows[0]->getIncomeClassification()[0]->getId());
        $this->assertCount(1, $rows[0]->getExpensesClassification());
        $this->assertEquals(100, $rows[0]->getExpensesClassification()[0]->getAmount());
        $this->assertEquals(2, $rows[0]->getExpensesClassification()[0]->getId());

        $this->assertCount(1, $rows[1]->getIncomeClassification());
        $this->assertEquals(10, $rows[1]->getIncomeClassification()[0]->getAmount());
        $this->assertEquals(1, $rows[1]->getIncomeClassification()[0]->getId());
        $this->assertCount(1, $rows[1]->getExpensesClassification());
        $this->assertEquals(10, $rows[1]->getExpensesClassification()[0]->getAmount());
        $this->assertEquals(2, $rows[1]->getExpensesClassification()[0]->getId());
    }

    public function test_vat_amount_tolerance_splits_rows_per_category(): void
    {
        $invoice = $this->invoiceWithRows([[10, 2.40], [10, 1.30, VatCategory::VAT_2], [10, 2.40]]);

        $invoice->squashInvoiceRows(['vatAmountTolerance' => 1.00]);
        $rows = $invoice->getInvoiceDetails();

        $this->assertCount(2, $rows);
        $this->assertEquals(VatCategory::VAT_1, $rows[0]->getVatCategory());
        $this->assertEquals(20, $rows[0]->getNetValue());
        $this->assertEquals(VatCategory::VAT_2, $rows[1]->getVatCategory());
        $this->assertEquals(10, $rows[1]->getNetValue());
    }

    public function test_groups_returns_the_rows_squashed_into_each_row(): void
    {
        $groups = (new SquashInvoiceRows())->groups($this->hotelRows());

        $this->assertSame([[1, 3, 6, 7], [2, 5], [4]], $this->lineNumbers($groups));
    }

    public function test_groups_splits_the_rows_a_vat_amount_tolerance_splits(): void
    {
        $groups = (new SquashInvoiceRows())->groups($this->hotelRows(), ['vatAmountTolerance' => 1.00]);

        $this->assertSame([[1, 3, 6], [2, 5], [7], [4]], $this->lineNumbers($groups));
    }

    public function test_squashed_rows_are_the_sums_of_their_groups(): void
    {
        $groups = (new SquashInvoiceRows())->groups($this->hotelRows(), ['vatAmountTolerance' => 1.00]);
        $rows = (new SquashInvoiceRows())->handle($this->hotelRows(), ['vatAmountTolerance' => 1.00]);

        $this->assertCount(count($groups), $rows);

        foreach ($groups as $index => $group) {
            $netValue = round(array_sum(array_map(fn (InvoiceDetails $row) => $row->getNetValue(), $group)), 2);
            $vatAmount = round(array_sum(array_map(fn (InvoiceDetails $row) => $row->getVatAmount(), $group)), 2);

            $this->assertSame($netValue, $rows[$index]->getNetValue());
            $this->assertSame($vatAmount, $rows[$index]->getVatAmount());
            $this->assertSame($group[0]->getRecType(), $rows[$index]->getRecType());
        }
    }

    public function test_squashing_leaves_the_original_rows_with_a_rec_type_unchanged(): void
    {
        $invoice = new Invoice();
        $invoice->addInvoiceDetails(new InvoiceDetails(['lineNumber' => 1, 'vatCategory' => VatCategory::VAT_1, 'netValue' => 10, 'vatAmount' => 2.40, 'recType' => RecType::TYPE_2]));
        $invoice->addInvoiceDetails(new InvoiceDetails(['lineNumber' => 2, 'vatCategory' => VatCategory::VAT_1, 'netValue' => 10, 'vatAmount' => 2.40]));
        $invoice->addInvoiceDetails(new InvoiceDetails(['lineNumber' => 3, 'vatCategory' => VatCategory::VAT_1, 'netValue' => 10, 'vatAmount' => 2.40]));

        $invoice->squashInvoiceRows();
        $this->assertSame([1, 2], array_map(fn (InvoiceDetails $row) => $row->getLineNumber(), $invoice->getInvoiceDetails()));

        $invoice->unSquashInvoiceRows();
        $this->assertSame([1, 2, 3], array_map(fn (InvoiceDetails $row) => $row->getLineNumber(), $invoice->getInvoiceDetails()));
    }

    public function test_a_reused_squasher_does_not_carry_rows_over(): void
    {
        $squasher = new SquashInvoiceRows();
        $squasher->handle($this->hotelRows());

        $rows = $squasher->handle([new InvoiceDetails(['vatCategory' => VatCategory::VAT_1, 'netValue' => 10, 'vatAmount' => 2.40])]);

        $this->assertCount(1, $rows);
        $this->assertSame(10.0, $rows[0]->getNetValue());
    }

    /**
     * Rooms at 24%, breakfasts at 13% one cent short, a city tax row with a recType and
     * two extra beds 0.60 over each, so that both no longer fit the rooms' row together.
     *
     * @return InvoiceDetails[]
     */
    private function hotelRows(): array
    {
        return [
            new InvoiceDetails(['lineNumber' => 1, 'vatCategory' => VatCategory::VAT_1, 'netValue' => 89.60, 'vatAmount' => 21.50]),
            new InvoiceDetails(['lineNumber' => 2, 'vatCategory' => VatCategory::VAT_2, 'netValue' => 5.27, 'vatAmount' => 0.68]),
            new InvoiceDetails(['lineNumber' => 3, 'vatCategory' => VatCategory::VAT_1, 'netValue' => 89.60, 'vatAmount' => 21.50]),
            new InvoiceDetails(['lineNumber' => 4, 'vatCategory' => VatCategory::VAT_2, 'netValue' => 0.04, 'vatAmount' => 0.01, 'recType' => RecType::TYPE_2]),
            new InvoiceDetails(['lineNumber' => 5, 'vatCategory' => VatCategory::VAT_2, 'netValue' => 5.27, 'vatAmount' => 0.68]),
            new InvoiceDetails(['lineNumber' => 6, 'vatCategory' => VatCategory::VAT_1, 'netValue' => 10.00, 'vatAmount' => 3.00]),
            new InvoiceDetails(['lineNumber' => 7, 'vatCategory' => VatCategory::VAT_1, 'netValue' => 10.00, 'vatAmount' => 3.00]),
        ];
    }

    /**
     * @param InvoiceDetails[][] $groups
     * @return int[][]
     */
    private function lineNumbers(array $groups): array
    {
        return array_map(fn (array $group) => array_map(fn (InvoiceDetails $row) => $row->getLineNumber(), $group), $groups);
    }

    /**
     * @param array<int, array{0: float, 1: float, 2?: VatCategory}> $rows Net value, vat amount and vat category of each row.
     */
    private function invoiceWithRows(array $rows): Invoice
    {
        $invoice = new Invoice();

        foreach ($rows as $row) {
            $invoice->addInvoiceDetails(new InvoiceDetails([
                'vatCategory' => $row[2] ?? VatCategory::VAT_1,
                'netValue' => $row[0],
                'vatAmount' => $row[1],
                'incomeClassification' => [
                    [
                        'classificationCategory' => IncomeClassificationCategory::CATEGORY_1_1,
                        'classificationType' => IncomeClassificationType::E3_561_001,
                        'amount' => $row[0],
                    ]
                ]
            ]));
        }

        return $invoice;
    }
}
