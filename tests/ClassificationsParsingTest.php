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
        $doc = (new RequestedDocReader())->parseXML($this->xml('income', $this->detail('income', 1, ['E3_561_001'])));

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
        $xml = $this->xml('income', 
            $this->detail('income', 1, ['E3_561_001', 'E3_561_002']).
            $this->detail('income', 2, ['E3_561_003'])
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
        $doc = (new RequestedDocReader())->parseXML($this->xml('expenses', $this->detail('expenses', 1, ['E3_102_001'])));

        $classification = $doc->getExpensesClassifications()->first();
        $this->assertInstanceOf(InvoiceExpensesClassification::class, $classification);

        $details = $classification->getInvoicesExpensesClassificationDetails();
        $this->assertCount(1, $details);
        $this->assertCount(1, $details[0]->getExpensesClassificationDetailData());
        $this->assertSame(ExpenseClassificationType::E3_102_001, $details[0]->getExpensesClassificationDetailData()[0]->getClassificationType());
    }

    public function test_it_keeps_every_repeated_expenses_classification_detail_and_detail_data(): void
    {
        $xml = $this->xml('expenses', 
            $this->detail('expenses', 1, ['E3_102_001', 'E3_102_002']).
            $this->detail('expenses', 2, ['E3_102_003'])
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

    /** Namespace prefix and a classification category per kind */
    private const KINDS = ['income' => ['icls', 'category1_1'], 'expenses' => ['ecls', 'category2_1']];

    private function detail(string $kind, int $lineNumber, array $types): string
    {
        [$ns, $category] = self::KINDS[$kind];
        $element = 'invoices'.ucfirst($kind).'ClassificationDetails';

        $data = '';
        foreach ($types as $type) {
            $data .= "<{$kind}ClassificationDetailData><$ns:classificationType>$type</$ns:classificationType><$ns:classificationCategory>$category</$ns:classificationCategory><$ns:amount>100</$ns:amount></{$kind}ClassificationDetailData>";
        }

        return "<$element><lineNumber>$lineNumber</lineNumber>$data</$element>";
    }

    private function xml(string $kind, string $details): string
    {
        $ns = self::KINDS[$kind][0];

        return '<?xml version="1.0" encoding="UTF-8"?>'
            ."<RequestedDoc xmlns=\"http://www.aade.gr/myDATA/invoice/v1.0\" xmlns:$ns=\"https://www.aade.gr/myDATA/{$kind}Classificaton/v1.0\">"
            ."<{$kind}ClassificationsDoc><{$kind}InvoiceClassification>"
            .'<invoiceMark>400001000000100</invoiceMark><classificationMark>400001000000300</classificationMark>'
            .$details
            ."</{$kind}InvoiceClassification></{$kind}ClassificationsDoc></RequestedDoc>";
    }
}
