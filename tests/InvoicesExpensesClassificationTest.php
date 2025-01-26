<?php

namespace Tests;

use Firebed\AadeMyData\Enums\ExpenseClassificationCategory;
use Firebed\AadeMyData\Enums\ExpenseClassificationType;
use Firebed\AadeMyData\Models\ExpensesClassification;
use Firebed\AadeMyData\Models\ExpensesClassificationsDoc;
use Firebed\AadeMyData\Models\InvoiceExpensesClassification;
use Firebed\AadeMyData\Models\InvoicesExpensesClassificationDetail;
use Firebed\AadeMyData\Xml\ExpensesClassificationsDocWriter;
use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;
use Tests\Xml\Document;

class InvoicesExpensesClassificationTest extends TestCase
{
    use HandlesInvoiceXml;

    public function test_it_converts_simple_expenses_invoice_classification_to_xml(): void
    {
        $cls = new InvoicesExpensesClassificationDetail();
        $cls->setLineNumber(1);
        $cls->setExpensesClassificationDetailData([
            new ExpensesClassification([
                'classificationType' => ExpenseClassificationType::E3_102_001,
                'classificationCategory' => ExpenseClassificationCategory::CATEGORY_2_1,
                'amount' => 100,
            ]),
        ]);

        $invoiceExpensesClassification = new InvoiceExpensesClassification();
        $invoiceExpensesClassification->setInvoiceMark(1234567890);
        $invoiceExpensesClassification->setInvoicesExpensesClassificationDetails([$cls]);

        $writer = new ExpensesClassificationsDocWriter();
        $xmlString = $writer->asXML(new ExpensesClassificationsDoc($invoiceExpensesClassification));
        $xml = (new Document($xmlString))->ExpensesClassificationsDoc->expensesInvoiceClassification;

        $this->assertEquals($invoiceExpensesClassification->getInvoiceMark(), $xml->invoiceMark);
        $this->assertEquals($cls->getLineNumber(), $xml->invoicesExpensesClassificationDetails->lineNumber);

        $details = $xml->invoicesExpensesClassificationDetails->expensesClassificationDetailData;
        $this->assertEquals($cls->getExpensesClassificationDetailData()[0]->getClassificationType()->value, $details->classificationType);
        $this->assertEquals($cls->getExpensesClassificationDetailData()[0]->getClassificationCategory()->value, $details->classificationCategory);
        $this->assertEquals($cls->getExpensesClassificationDetailData()[0]->getAmount(), $details->amount);
    }

    public function test_it_converts_complex_expenses_invoice_classification_to_xml(): void
    {
        $clsA = new InvoicesExpensesClassificationDetail();
        $clsA->setLineNumber(1);
        $clsA->setExpensesClassificationDetailData([
            new ExpensesClassification([
                'classificationType' => ExpenseClassificationType::E3_102_001,
                'classificationCategory' => ExpenseClassificationCategory::CATEGORY_2_1,
                'amount' => 100,
            ]),
            new ExpensesClassification([
                'classificationType' => ExpenseClassificationType::E3_102_003,
                'classificationCategory' => ExpenseClassificationCategory::CATEGORY_2_1,
                'amount' => 300,
            ]),
        ]);

        $clsB = new InvoicesExpensesClassificationDetail();
        $clsB->setLineNumber(2);
        $clsB->setExpensesClassificationDetailData([
            new ExpensesClassification([
                'classificationType' => ExpenseClassificationType::E3_102_002,
                'classificationCategory' => ExpenseClassificationCategory::CATEGORY_2_1,
                'amount' => 50,
            ])
        ]);

        $invoiceExpensesClassification = new InvoiceExpensesClassification();
        $invoiceExpensesClassification->setInvoiceMark(1234567890);
        $invoiceExpensesClassification->setInvoicesExpensesClassificationDetails([$clsA, $clsB]);

        $writer = new ExpensesClassificationsDocWriter();
        $xmlString = $writer->asXML(new ExpensesClassificationsDoc($invoiceExpensesClassification));

        $xml = (new Document($xmlString))->ExpensesClassificationsDoc->expensesInvoiceClassification;

        $this->assertEquals($invoiceExpensesClassification->getInvoiceMark(), $xml->invoiceMark);
        $this->assertCount(2, $xml->invoicesExpensesClassificationDetails);

        $clsADoc = $xml->invoicesExpensesClassificationDetails[0];
        $this->assertEquals($clsA->getLineNumber(), $clsADoc->lineNumber);
        $this->assertCount(2, $clsADoc->expensesClassificationDetailData);

        $clsData = $clsADoc->expensesClassificationDetailData[0];
        $this->assertEquals($clsA->getExpensesClassificationDetailData()[0]->getClassificationType()->value, $clsData->classificationType);
        $this->assertEquals($clsA->getExpensesClassificationDetailData()[0]->getClassificationCategory()->value, $clsData->classificationCategory);
        $this->assertEquals($clsA->getExpensesClassificationDetailData()[0]->getAmount(), $clsData->amount);
    }
}