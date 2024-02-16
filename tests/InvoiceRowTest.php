<?php

namespace Tests;

use Firebed\AadeMyData\Enums\ExpenseClassificationCategory;
use Firebed\AadeMyData\Enums\ExpenseClassificationType;
use Firebed\AadeMyData\Enums\FeesPercentCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Enums\InvoiceDetailType;
use Firebed\AadeMyData\Enums\OtherTaxesPercentCategory;
use Firebed\AadeMyData\Enums\RecType;
use Firebed\AadeMyData\Enums\StampCategory;
use Firebed\AadeMyData\Enums\UnitMeasurement;
use Firebed\AadeMyData\Enums\VatCategory;
use Firebed\AadeMyData\Enums\VatExemption;
use Firebed\AadeMyData\Enums\WithheldPercentCategory;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoiceDetails;
use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;

class InvoiceRowTest extends TestCase
{
    use HandlesInvoiceXml;

    public function test_it_converts_invoice_rows_to_xml(): void
    {
        $row1 = new InvoiceDetails();
        $row1->setLineNumber(1);
        $row1->setRecType(RecType::TYPE_1);
        $row1->setTaricNo('AAADDDFFF888');
        $row1->setItemCode('520.005');
        $row1->setItemDescr('Φίλτρο Λαδιού');
        $row1->setFuelCode('AA55');
        $row1->setQuantity(3);
        $row1->setMeasurementUnit(UnitMeasurement::UNIT_2);
        $row1->setInvoiceDetailType(InvoiceDetailType::TYPE_1);
        $row1->setNetValue(10);
        $row1->setVatCategory(VatCategory::VAT_7);
        $row1->setVatAmount(0);
        $row1->setVatExemptionCategory(VatExemption::TYPE_28);
        $row1->setDienergia('ΠΟΛ-123', '2022-02-12', 'Β ΑΘΗΝΩΝ', '5566');
        $row1->setDiscountOption(true);
        $row1->setWithheldAmount(6);
        $row1->setWithheldPercentCategory(WithheldPercentCategory::TAX_3);
        $row1->setStampDutyAmount(56);
        $row1->setStampDutyPercentCategory(StampCategory::TYPE_2);
        $row1->setFeesAmount(12);
        $row1->setFeesPercentCategory(FeesPercentCategory::TYPE_4);
        $row1->setOtherTaxesPercentCategory(OtherTaxesPercentCategory::TAX_13);
        $row1->setOtherTaxesAmount(66);
        $row1->setDeductionsAmount(30);
        $row1->setLineComments('This is boring...');
        $row1->addIncomeClassification(IncomeClassificationType::E3_561_001, IncomeClassificationCategory::CATEGORY_1_2, 100);
        $row1->addIncomeClassification(IncomeClassificationType::E3_561_002, IncomeClassificationCategory::CATEGORY_1_3, 50);
        $row1->addExpensesClassification(ExpenseClassificationType::E3_104, ExpenseClassificationCategory::CATEGORY_2_11, 25);
        $row1->setQuantity15(10);
        $row1->setOtherMeasurementUnitQuantity(12);
        $row1->setOtherMeasurementUnitTitle('ΣΕΤ');

        $row2 = new InvoiceDetails();
        $row2->setLineNumber(2);
        $row2->setNetValue(4.42);
        $row2->setVatCategory(VatCategory::VAT_2);
        $row2->setVatAmount(0.58);
        $row2->addIncomeClassification(IncomeClassificationType::E3_561_001, IncomeClassificationCategory::CATEGORY_1_2, 2.03);
        $row2->addIncomeClassification(IncomeClassificationType::E3_561_003, IncomeClassificationCategory::CATEGORY_1_3, 2.00);

        $invoice = new Invoice();
        $invoice->addInvoiceDetails($row1);
        $invoice->addInvoiceDetails($row2);

        $xml = $this->toXML($invoice)->InvoicesDoc->invoice;

        // Invoice rows assertions
        $this->assertCount(2, $xml->invoiceDetails);

        // First invoice row
        $rows = $xml->invoiceDetails;

        $this->assertCount(30, $rows[0]);
        $this->assertEquals(1, $rows[0]->lineNumber);
        $this->assertEquals(RecType::TYPE_1->value, $rows[0]->recType);
        $this->assertEquals('AAADDDFFF888', $rows[0]->TaricNo);
        $this->assertEquals('520.005', $rows[0]->itemCode);
        $this->assertEquals('Φίλτρο Λαδιού', $rows[0]->itemDescr);
        $this->assertEquals('AA55', $rows[0]->fuelCode);
        $this->assertEquals(3, $rows[0]->quantity);
        $this->assertEquals(UnitMeasurement::UNIT_2->value, $rows[0]->measurementUnit);
        $this->assertEquals(InvoiceDetailType::TYPE_1->value, $rows[0]->invoiceDetailType);
        $this->assertEquals(10, $rows[0]->netValue);
        $this->assertEquals(VatCategory::VAT_7->value, $rows[0]->vatCategory);
        $this->assertEquals(0, $rows[0]->vatAmount);
        $this->assertEquals(VatExemption::TYPE_28->value, $rows[0]->vatExemptionCategory);
        $this->assertEquals('ΠΟΛ-123', $rows[0]->dienergia->applicationId);
        $this->assertEquals('2022-02-12', $rows[0]->dienergia->applicationDate);
        $this->assertEquals('Β ΑΘΗΝΩΝ', $rows[0]->dienergia->doy);
        $this->assertEquals('5566', $rows[0]->dienergia->shipID);
        $this->assertTrue(filter_var($rows[0]->discountOption, FILTER_VALIDATE_BOOLEAN));
        $this->assertEquals(6, $rows[0]->withheldAmount);
        $this->assertEquals(WithheldPercentCategory::TAX_3->value, $rows[0]->withheldPercentCategory);
        $this->assertEquals(56, $rows[0]->stampDutyAmount);
        $this->assertEquals(StampCategory::TYPE_2->value, $rows[0]->stampDutyPercentCategory);
        $this->assertEquals(12, $rows[0]->feesAmount);
        $this->assertEquals(FeesPercentCategory::TYPE_4->value, $rows[0]->feesPercentCategory);
        $this->assertEquals(OtherTaxesPercentCategory::TAX_13->value, $rows[0]->otherTaxesPercentCategory);
        $this->assertEquals(66, $rows[0]->otherTaxesAmount);
        $this->assertEquals(30, $rows[0]->deductionsAmount);
        $this->assertEquals('This is boring...', $rows[0]->lineComments);
        $this->assertEquals(10, $rows[0]->quantity15);
        $this->assertEquals(12, $rows[0]->otherMeasurementUnitQuantity);
        $this->assertEquals('ΣΕΤ', $rows[0]->otherMeasurementUnitTitle);

        $this->assertIncomeClassification(
            IncomeClassificationType::E3_561_001,
            IncomeClassificationCategory::CATEGORY_1_2,
            100,
            $rows[0]->incomeClassification[0]
        );

        $this->assertIncomeClassification(
            IncomeClassificationType::E3_561_002,
            IncomeClassificationCategory::CATEGORY_1_3,
            50,
            $rows[0]->incomeClassification[1]
        );

        $this->assertExpensesClassification(
            ExpenseClassificationType::E3_104,
            ExpenseClassificationCategory::CATEGORY_2_11,
            25,
            $rows[0]->expensesClassification
        );

        // Second invoice row
        $this->assertCount(5, $rows[1]);

        $this->assertEquals(2, $rows[1]->lineNumber);
        $this->assertEquals(4.42, $rows[1]->netValue);
        $this->assertEquals(VatCategory::VAT_2->value, (int)$rows[1]->vatCategory);
        $this->assertEquals(0.58, $rows[1]->vatAmount);

        $this->assertIncomeClassification(
            IncomeClassificationType::E3_561_001,
            IncomeClassificationCategory::CATEGORY_1_2,
            2.03,
            $rows[1]->incomeClassification[0]
        );

        $this->assertIncomeClassification(
            IncomeClassificationType::E3_561_003,
            IncomeClassificationCategory::CATEGORY_1_3,
            2.00,
            $rows[1]->incomeClassification[1]
        );
    }

    public function test_it_converts_xml_to_invoice_rows(): void
    {
        $invoice = $this->getInvoiceFromXml();

        $rows = $invoice->getInvoiceDetails();

        $this->assertCount(1, $rows);

        $row = $rows[0];
        $this->assertEquals(1, $row->getLineNumber());
        $this->assertEquals(5000, $row->getNetValue());
        $this->assertEquals(VatCategory::VAT_1->value, $row->getVatCategory());
        $this->assertEquals(1200, $row->getVatAmount());

        $this->assertCount(2, $row->getExpensesClassification());

        $this->assertExpensesClassification(
            ExpenseClassificationType::E3_102_001,
            ExpenseClassificationCategory::CATEGORY_2_1,
            4000,
            $row->getExpensesClassification()[0]
        );

        $this->assertExpensesClassification(
            ExpenseClassificationType::VAT_361,
            ExpenseClassificationCategory::CATEGORY_2_1,
            1000,
            $row->getExpensesClassification()[1]
        );
    }
}
