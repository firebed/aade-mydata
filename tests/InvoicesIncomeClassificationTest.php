<?php

namespace Tests;

use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Models\IncomeClassification;
use Firebed\AadeMyData\Models\IncomeClassificationsDoc;
use Firebed\AadeMyData\Models\InvoiceIncomeClassification;
use Firebed\AadeMyData\Models\InvoicesIncomeClassificationDetail;
use Firebed\AadeMyData\Xml\IncomeClassificationsDocWriter;
use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;
use Tests\Xml\Document;

class InvoicesIncomeClassificationTest extends TestCase
{
    use HandlesInvoiceXml;

    public function test_it_converts_simple_income_invoice_classification_to_xml(): void
    {
        $cls = new InvoicesIncomeClassificationDetail();
        $cls->setLineNumber(1);
        $cls->setIncomeClassificationDetailData([
            new IncomeClassification([
                'classificationType' => IncomeClassificationType::E3_561_001,
                'classificationCategory' => IncomeClassificationCategory::CATEGORY_1_2,
                'amount' => 100,
            ]),
        ]);

        $invoiceIncomeClassification = new InvoiceIncomeClassification();
        $invoiceIncomeClassification->setInvoiceMark(1234567890);
        $invoiceIncomeClassification->setInvoicesIncomeClassificationDetails([$cls]);

        $writer = new IncomeClassificationsDocWriter();
        $xmlString = $writer->asXML(new IncomeClassificationsDoc($invoiceIncomeClassification));
        
        $xml = (new Document($xmlString))->IncomeClassificationsDoc->incomeInvoiceClassification;

        $this->assertEquals($invoiceIncomeClassification->getInvoiceMark(), $xml->invoiceMark);
        $this->assertEquals($cls->getLineNumber(), $xml->invoicesIncomeClassificationDetails->lineNumber);

        $details = $xml->invoicesIncomeClassificationDetails->incomeClassificationDetailData;
        $this->assertEquals($cls->getIncomeClassificationDetailData()[0]->getClassificationType()->value, $details->classificationType);
        $this->assertEquals($cls->getIncomeClassificationDetailData()[0]->getClassificationCategory()->value, $details->classificationCategory);
        $this->assertEquals($cls->getIncomeClassificationDetailData()[0]->getAmount(), $details->amount);
    }

    public function test_it_converts_complex_income_invoice_classification_to_xml(): void
    {
        $clsA = new InvoicesIncomeClassificationDetail();
        $clsA->setLineNumber(1);
        $clsA->setIncomeClassificationDetailData([
            new IncomeClassification([
                'classificationType' => IncomeClassificationType::E3_561_001,
                'classificationCategory' => IncomeClassificationCategory::CATEGORY_1_2,
                'amount' => 100,
            ]),
            new IncomeClassification([
                'classificationType' => IncomeClassificationType::E3_561_002,
                'classificationCategory' => IncomeClassificationCategory::CATEGORY_1_2,
                'amount' => 300,
            ]),
        ]);

        $clsB = new InvoicesIncomeClassificationDetail();
        $clsB->setLineNumber(2);
        $clsB->setIncomeClassificationDetailData([
            new IncomeClassification([
                'classificationType' => IncomeClassificationType::E3_561_002,
                'classificationCategory' => IncomeClassificationCategory::CATEGORY_1_2,
                'amount' => 50,
            ])
        ]);

        $invoiceIncomeClassification = new InvoiceIncomeClassification();
        $invoiceIncomeClassification->setInvoiceMark(1234567890);
        $invoiceIncomeClassification->setInvoicesIncomeClassificationDetails([$clsA, $clsB]);

        $writer = new IncomeClassificationsDocWriter();
        $xmlString = $writer->asXML(new IncomeClassificationsDoc($invoiceIncomeClassification));

        $xml = (new Document($xmlString))->IncomeClassificationsDoc->incomeInvoiceClassification;

        $this->assertEquals($invoiceIncomeClassification->getInvoiceMark(), $xml->invoiceMark);
        $this->assertCount(2, $xml->invoicesIncomeClassificationDetails);

        $clsADoc = $xml->invoicesIncomeClassificationDetails[0];
        $this->assertEquals($clsA->getLineNumber(), $clsADoc->lineNumber);
        $this->assertCount(2, $clsADoc->incomeClassificationDetailData);

        $clsData = $clsADoc->incomeClassificationDetailData[0];
        $this->assertEquals($clsA->getIncomeClassificationDetailData()[0]->getClassificationType()->value, $clsData->classificationType);
        $this->assertEquals($clsA->getIncomeClassificationDetailData()[0]->getClassificationCategory()->value, $clsData->classificationCategory);
        $this->assertEquals($clsA->getIncomeClassificationDetailData()[0]->getAmount(), $clsData->amount);
    }
}