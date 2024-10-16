<?php

namespace Tests;

use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Enums\RecType;
use Firebed\AadeMyData\Models\Invoice;
use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;

class InvoiceTest extends TestCase
{
    use HandlesInvoiceXml;

    public function test_invoice_xml()
    {
        $invoice = Invoice::factory()->make([
            'invoiceDetails' => [
                [
                    'lineNumber' => 1,
                    'netValue' => 5,
                    'recType' => RecType::TYPE_2,
                    'incomeClassification' => [
                        [
                            'classificationType' => IncomeClassificationType::E3_561_001,
                            'classificationCategory' => IncomeClassificationCategory::CATEGORY_1_1,
                            'amount' => 5
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertNotEmpty($invoice->toXml());
    }

    public function test_invoice_xml_with_category_1_95()
    {
        $invoice = new Invoice([
            'invoiceDetails' => [
                [
                    'lineNumber' => 1,
                    'netValue' => 5,
                    'recType' => RecType::TYPE_2,
                    'incomeClassification' => [
                        [
                            'classificationType' => '',
                            'classificationCategory' => IncomeClassificationCategory::CATEGORY_1_95,
                            'amount' => 5
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertNotEmpty($invoice->toXml());
        $this->assertNull($invoice->getInvoiceDetails()[0]->getIncomeClassification()[0]->getClassificationType());
    }

    public function test_it_populates_invoice_auto_filled_attributes()
    {
        $doc = $this->getRequestedDocFromXml();

        $this->assertCount(2, $doc->getInvoices());

        $invoice = $doc->getInvoices()[1];

        $this->assertEquals('5AD65A46SFD5498SDV416WS5F1VS65VDFS65VDF', $invoice->getUid());
        $this->assertEquals('800000165789544', $invoice->getMark());
        $this->assertEquals('800000165989544', $invoice->getCancelledByMark());
        $this->assertEquals('54AS56DS65VF4S65DF', $invoice->getAuthenticationCode());
        $this->assertEquals(3, $invoice->getTransmissionFailure());
        $this->assertEquals('https://www.akjjasd.com/asdkasdkasdkas?asasd=asdasd', $invoice->getQrCodeUrl());
    }

    public function test_invoice_validation()
    {
        $invoice = Invoice::factory()->make();
        $this->assertEmpty($invoice->validate(), "Validation failed: ".print_r($invoice->validate(), true));
    }

    public function test_invoice_validation_fail()
    {
        $invoice = Invoice::factory()->make();
        $invoice->getInvoiceHeader()->setInvoiceType("wrong");
        $invoice->getInvoiceSummary()->setTotalGrossValue(-10);

        // "wrong" value will be cast to null because InvoiceType enum does
        // not have "wrong" value.
        // Since null values are stripped from the array, we expect a failure
        // that the next give attribute (vatPaymentSuspension) is not expected
        $this->assertEquals([
            "field" => "{http://www.aade.gr/myDATA/invoice/v1.0}vatPaymentSuspension",
            "message" => "This element is not expected. Expected is ( {http://www.aade.gr/myDATA/invoice/v1.0}invoiceType )."
        ], $invoice->validate()[0]);

        $this->assertEquals([
            "field" => "{http://www.aade.gr/myDATA/invoice/v1.0}totalGrossValue",
            "message" => "The value '-10' is less than the minimum value allowed ('0')."
        ], $invoice->validate()[1]);
    }
}