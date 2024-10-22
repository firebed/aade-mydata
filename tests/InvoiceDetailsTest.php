<?php

namespace Tests;

use Firebed\AadeMyData\Enums\ExpenseClassificationCategory;
use Firebed\AadeMyData\Enums\ExpenseClassificationType;
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Enums\VatCategory;
use Firebed\AadeMyData\Enums\VatExemption;
use Firebed\AadeMyData\Models\ExpensesClassification;
use Firebed\AadeMyData\Models\IncomeClassification;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoiceDetails;
use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;

class InvoiceDetailsTest extends TestCase
{
    use HandlesInvoiceXml;

    public function test_it_converts_single_invoice_row_to_xml(): void
    {
        $invoice = Invoice::factory()->make();

        $rows = $invoice->getInvoiceDetails();

        $rowXml = $this->toXML($invoice)->InvoicesDoc->invoice->invoiceDetails;

        $this->assertCount(1, $rows);

        // Invoice rows assertions
        $this->assertCount(31, $rowXml);

        $row = $rows[0];
        $this->assertEquals($row->getLineNumber(), $rowXml->lineNumber);
        $this->assertEquals($row->getRecType()->value, $rowXml->recType);
        $this->assertEquals($row->getTaricNo(), $rowXml->TaricNo);
        $this->assertEquals($row->getItemCode(), $rowXml->itemCode);
        $this->assertEquals($row->getItemDescr(), $rowXml->itemDescr);
        $this->assertEquals($row->getFuelCode()->value, $rowXml->fuelCode);
        $this->assertEquals($row->getQuantity(), $rowXml->quantity);
        $this->assertEquals($row->getMeasurementUnit()->value, $rowXml->measurementUnit);
        $this->assertEquals($row->getInvoiceDetailType()->value, $rowXml->invoiceDetailType);
        $this->assertEquals($row->getNetValue(), $rowXml->netValue);
        $this->assertEquals($row->getVatCategory()->value, $rowXml->vatCategory);
        $this->assertEquals($row->getVatAmount(), $rowXml->vatAmount);
        $this->assertEquals($row->getVatExemptionCategory()->value, $rowXml->vatExemptionCategory);
        $this->assertEquals($row->getDienergia()->getApplicationId(), $rowXml->dienergia->applicationId);
        $this->assertEquals($row->getDienergia()->getApplicationDate(), $rowXml->dienergia->applicationDate);
        $this->assertEquals($row->getDienergia()->getDoy(), $rowXml->dienergia->doy);
        $this->assertEquals($row->getDienergia()->getShipId(), $rowXml->dienergia->shipId);
        $this->assertEquals($row->isDiscountOption(), filter_var($rowXml->discountOption, FILTER_VALIDATE_BOOLEAN));
        $this->assertEquals($row->getWithheldAmount(), $rowXml->withheldAmount);
        $this->assertEquals($row->getWithheldPercentCategory()->value, $rowXml->withheldPercentCategory);
        $this->assertEquals($row->getStampDutyAmount(), $rowXml->stampDutyAmount);
        $this->assertEquals($row->getStampDutyPercentCategory()->value, $rowXml->stampDutyPercentCategory);
        $this->assertEquals($row->getFeesAmount(), $rowXml->feesAmount);
        $this->assertEquals($row->getFeesPercentCategory()->value, $rowXml->feesPercentCategory);
        $this->assertEquals($row->getOtherTaxesPercentCategory()->value, $rowXml->otherTaxesPercentCategory);
        $this->assertEquals($row->getOtherTaxesAmount(), $rowXml->otherTaxesAmount);
        $this->assertEquals($row->getDeductionsAmount(), $rowXml->deductionsAmount);
        $this->assertEquals($row->getLineComments(), $rowXml->lineComments);
        $this->assertEquals($row->getQuantity15(), $rowXml->quantity15);
        $this->assertEquals($row->getOtherMeasurementUnitQuantity(), $rowXml->otherMeasurementUnitQuantity);
        $this->assertEquals($row->getOtherMeasurementUnitTitle(), $rowXml->otherMeasurementUnitTitle);
        $this->assertEquals($row->getNotVAT195(), filter_var($rowXml->notVAT195, FILTER_VALIDATE_BOOLEAN));

        $icls = $invoice->getInvoiceDetails()[0]->getIncomeClassification()[0];
        $this->assertEquals($icls->getClassificationType()->value, $rowXml->incomeClassification->get('icls:classificationType'));
        $this->assertEquals($icls->getClassificationCategory()->value, $rowXml->incomeClassification->get('icls:classificationCategory'));
        $this->assertEquals($icls->getAmount(), $rowXml->incomeClassification->get('icls:amount'));
        $this->assertEquals($icls->getId(), $rowXml->incomeClassification->get('icls:id'));

        $ecls = $invoice->getInvoiceDetails()[0]->getExpensesClassification()[0];

        $this->assertEquals($ecls->getClassificationType()->value, $rowXml->expensesClassification->get('ecls:classificationType'));
        $this->assertEquals($ecls->getClassificationCategory()->value, $rowXml->expensesClassification->get('ecls:classificationCategory'));
        $this->assertEquals($ecls->getAmount(), $rowXml->expensesClassification->get('ecls:amount'));
        $this->assertEquals($ecls->getVatCategory()->value, $rowXml->expensesClassification->get('ecls:vatCategory'));
        $this->assertEquals($ecls->getVatExemptionCategory()->value, $rowXml->expensesClassification->get('ecls:vatExemptionCategory'));
        $this->assertEquals($ecls->getId(), $rowXml->expensesClassification->get('ecls:id'));
    }

    public function test_it_converts_multiple_invoice_rows_to_xml(): void
    {
        $invoice = Invoice::factory()
            ->state([
                'invoiceDetails' => InvoiceDetails::factory(2)->state([
                    'incomeClassification' => IncomeClassification::factory(2),
                    'expensesClassification' => ExpensesClassification::factory(2),
                ])
            ])
            ->make();

        $rows = $invoice->getInvoiceDetails();
        $rowsXml = $this->toXML($invoice)->InvoicesDoc->invoice->invoiceDetails;

        $this->assertCount(2, $rows);
        $this->assertCount(2, $rowsXml);

        $this->assertCount(2, $rowsXml[0]->incomeClassification);
        $this->assertCount(2, $rowsXml[0]->expensesClassification);

        $this->assertCount(2, $rowsXml[1]->incomeClassification);
        $this->assertCount(2, $rowsXml[1]->expensesClassification);
    }

    public function test_it_converts_xml_to_invoice_rows(): void
    {
        $invoice = $this->getInvoiceFromXml();

        $rows = $invoice->getInvoiceDetails();

        $this->assertCount(1, $rows);

        $row = $rows[0];
        $this->assertEquals(1, $row->getLineNumber());
        $this->assertEquals(5000, $row->getNetValue());
        $this->assertEquals(VatCategory::VAT_1, $row->getVatCategory());
        $this->assertEquals(1200, $row->getVatAmount());

        $this->assertCount(1, $row->getIncomeClassification());

        $icls = $row->getIncomeClassification()[0];
        $this->assertEquals(IncomeClassificationType::E3_561_001, $icls->getClassificationType());
        $this->assertEquals(IncomeClassificationCategory::CATEGORY_1_1, $icls->getClassificationCategory());
        $this->assertEquals(4000, $icls->getAmount());
        $this->assertEquals(1, $icls->getId());

        $this->assertCount(2, $row->getExpensesClassification());
        $ecls1 = $row->getExpensesClassification()[0];
        $this->assertEquals(ExpenseClassificationType::E3_102_001, $ecls1->getClassificationType());
        $this->assertEquals(ExpenseClassificationCategory::CATEGORY_2_1, $ecls1->getClassificationCategory());
        $this->assertEquals(3000, $ecls1->getAmount());
        $this->assertEquals(VatCategory::VAT_4, $ecls1->getVatCategory());
        $this->assertEquals(VatExemption::TYPE_12, $ecls1->getVatExemptionCategory());
        $this->assertEquals(2, $ecls1->getId());

        $ecls2 = $row->getExpensesClassification()[1];
        $this->assertEquals(ExpenseClassificationType::VAT_361, $ecls2->getClassificationType());
        $this->assertEquals(ExpenseClassificationCategory::CATEGORY_2_1, $ecls2->getClassificationCategory());
        $this->assertEquals(1000, $ecls2->getAmount());
        $this->assertEquals(3, $ecls2->getId());
    }
}
