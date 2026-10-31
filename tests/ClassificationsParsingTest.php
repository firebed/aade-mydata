<?php

namespace Tests;

use Firebed\AadeMyData\Enums\ExpenseClassificationType;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Models\ExpensesClassification;
use Firebed\AadeMyData\Models\IncomeClassification;
use Firebed\AadeMyData\Models\InvoiceExpensesClassification;
use Firebed\AadeMyData\Models\InvoiceIncomeClassification;
use Firebed\AadeMyData\Models\InvoicesExpensesClassificationDetail;
use Firebed\AadeMyData\Models\InvoicesIncomeClassificationDetail;
use Firebed\AadeMyData\Xml\RequestedDocReader;
use PHPUnit\Framework\TestCase;

class ClassificationsParsingTest extends TestCase
{
    public function test_it_parses_a_single_income_classification_detail_as_a_list(): void
    {
        $doc = (new RequestedDocReader())->parseXML($this->incomeXml($this->incomeDetail(1, ['E3_561_001'])));

        $classification = $doc->getIncomeClassifications()->first();
        $this->assertInstanceOf(InvoiceIncomeClassification::class, $classification);
        $this->assertSame(400001000000100, $classification->getInvoiceMark());
        $this->assertSame(400001000000300, $classification->getClassificationMark());

        $details = $classification->getInvoicesIncomeClassificationDetails();
        $this->assertCount(1, $details);
        $this->assertSame(1, $details[0]->getLineNumber());

        $data = $details[0]->getIncomeClassificationDetailData();
        $this->assertCount(1, $data);
        $this->assertSame(IncomeClassificationType::E3_561_001, $data[0]->getClassificationType());
    }

    public function test_it_keeps_every_repeated_income_classification_detail_and_detail_data(): void
    {
        $xml = $this->incomeXml(
            $this->incomeDetail(1, ['E3_561_001', 'E3_561_002']).
            $this->incomeDetail(2, ['E3_561_003'])
        );

        $doc = (new RequestedDocReader())->parseXML($xml);
        $details = $doc->getIncomeClassifications()->first()->getInvoicesIncomeClassificationDetails();

        $this->assertCount(2, $details);
        $this->assertSame([1, 2], array_map(fn(InvoicesIncomeClassificationDetail $d) => $d->getLineNumber(), $details));

        $this->assertSame(
            [IncomeClassificationType::E3_561_001, IncomeClassificationType::E3_561_002],
            array_map(fn(IncomeClassification $c) => $c->getClassificationType(), $details[0]->getIncomeClassificationDetailData())
        );
        $this->assertSame(
            [IncomeClassificationType::E3_561_003],
            array_map(fn(IncomeClassification $c) => $c->getClassificationType(), $details[1]->getIncomeClassificationDetailData())
        );
    }

    public function test_it_parses_a_single_expenses_classification_detail_as_a_list(): void
    {
        $doc = (new RequestedDocReader())->parseXML($this->expensesXml($this->expensesDetail(1, ['E3_102_001'])));

        $classification = $doc->getExpensesClassifications()->first();
        $this->assertInstanceOf(InvoiceExpensesClassification::class, $classification);

        $details = $classification->getInvoicesExpensesClassificationDetails();
        $this->assertCount(1, $details);
        $this->assertCount(1, $details[0]->getExpensesClassificationDetailData());
        $this->assertSame(ExpenseClassificationType::E3_102_001, $details[0]->getExpensesClassificationDetailData()[0]->getClassificationType());
    }

    public function test_it_keeps_every_repeated_expenses_classification_detail_and_detail_data(): void
    {
        $xml = $this->expensesXml(
            $this->expensesDetail(1, ['E3_102_001', 'E3_102_002']).
            $this->expensesDetail(2, ['E3_102_003'])
        );

        $doc = (new RequestedDocReader())->parseXML($xml);
        $details = $doc->getExpensesClassifications()->first()->getInvoicesExpensesClassificationDetails();

        $this->assertCount(2, $details);
        $this->assertSame([1, 2], array_map(fn(InvoicesExpensesClassificationDetail $d) => $d->getLineNumber(), $details));

        $this->assertSame(
            [ExpenseClassificationType::E3_102_001, ExpenseClassificationType::E3_102_002],
            array_map(fn(ExpensesClassification $c) => $c->getClassificationType(), $details[0]->getExpensesClassificationDetailData())
        );
        $this->assertSame(
            [ExpenseClassificationType::E3_102_003],
            array_map(fn(ExpensesClassification $c) => $c->getClassificationType(), $details[1]->getExpensesClassificationDetailData())
        );
    }

    public function test_array_setters_still_replace_the_whole_list(): void
    {
        $income = new InvoiceIncomeClassification();
        $income->addInvoicesIncomeClassificationDetails(new InvoicesIncomeClassificationDetail(['lineNumber' => 1]));
        $income->setInvoicesIncomeClassificationDetails([new InvoicesIncomeClassificationDetail(['lineNumber' => 2])]);
        $this->assertCount(1, $income->getInvoicesIncomeClassificationDetails());
        $this->assertSame(2, $income->getInvoicesIncomeClassificationDetails()[0]->getLineNumber());

        $income->setInvoicesIncomeClassificationDetails(null);
        $this->assertNull($income->getInvoicesIncomeClassificationDetails());

        $incomeDetail = new InvoicesIncomeClassificationDetail();
        $incomeDetail->addIncomeClassificationDetailData(new IncomeClassification(['amount' => 1]));
        $incomeDetail->setIncomeClassificationDetailData([new IncomeClassification(['amount' => 2])]);
        $this->assertCount(1, $incomeDetail->getIncomeClassificationDetailData());

        $expenses = new InvoiceExpensesClassification();
        $expenses->addInvoicesExpensesClassificationDetails(new InvoicesExpensesClassificationDetail(['lineNumber' => 1]));
        $expenses->setInvoicesExpensesClassificationDetails([new InvoicesExpensesClassificationDetail(['lineNumber' => 2])]);
        $this->assertCount(1, $expenses->getInvoicesExpensesClassificationDetails());

        $expensesDetail = new InvoicesExpensesClassificationDetail();
        $expensesDetail->addExpensesClassificationDetailData(new ExpensesClassification(['amount' => 1]));
        $expensesDetail->setExpensesClassificationDetailData([new ExpensesClassification(['amount' => 2])]);
        $this->assertCount(1, $expensesDetail->getExpensesClassificationDetailData());
    }

    private function incomeDetail(int $lineNumber, array $types): string
    {
        $data = '';
        foreach ($types as $type) {
            $data .= "<incomeClassificationDetailData><icls:classificationType>$type</icls:classificationType><icls:classificationCategory>category1_1</icls:classificationCategory><icls:amount>100</icls:amount></incomeClassificationDetailData>";
        }

        return "<invoicesIncomeClassificationDetails><lineNumber>$lineNumber</lineNumber>$data</invoicesIncomeClassificationDetails>";
    }

    private function expensesDetail(int $lineNumber, array $types): string
    {
        $data = '';
        foreach ($types as $type) {
            $data .= "<expensesClassificationDetailData><ecls:classificationType>$type</ecls:classificationType><ecls:classificationCategory>category2_1</ecls:classificationCategory><ecls:amount>100</ecls:amount></expensesClassificationDetailData>";
        }

        return "<invoicesExpensesClassificationDetails><lineNumber>$lineNumber</lineNumber>$data</invoicesExpensesClassificationDetails>";
    }

    private function incomeXml(string $details): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>'
            .'<RequestedDoc xmlns="http://www.aade.gr/myDATA/invoice/v1.0" xmlns:icls="https://www.aade.gr/myDATA/incomeClassificaton/v1.0">'
            .'<incomeClassificationsDoc><incomeInvoiceClassification>'
            .'<invoiceMark>400001000000100</invoiceMark><classificationMark>400001000000300</classificationMark>'
            .$details
            .'</incomeInvoiceClassification></incomeClassificationsDoc></RequestedDoc>';
    }

    private function expensesXml(string $details): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>'
            .'<RequestedDoc xmlns="http://www.aade.gr/myDATA/invoice/v1.0" xmlns:ecls="https://www.aade.gr/myDATA/expensesClassificaton/v1.0">'
            .'<expensesClassificationsDoc><expensesInvoiceClassification>'
            .'<invoiceMark>400001000000100</invoiceMark><classificationMark>400001000000300</classificationMark>'
            .$details
            .'</expensesInvoiceClassification></expensesClassificationsDoc></RequestedDoc>';
    }
}
