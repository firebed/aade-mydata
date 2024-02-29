<?php

namespace Tests;

use Firebed\AadeMyData\Enums\ExpenseClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Models\ExpensesClassification;
use Firebed\AadeMyData\Models\IncomeClassification;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoiceDetails;
use PHPUnit\Framework\TestCase;
use Tests\Traits\HandlesInvoiceXml;

class XmlNullValuesTest extends TestCase
{
    use HandlesInvoiceXml;

    public function test_null_values_are_removed_from_generated_xml()
    {
        $icls1 = new IncomeClassification();
        $icls1->setClassificationType(null);
        $icls1->setClassificationCategory(IncomeClassificationCategory::CATEGORY_3);
        $icls1->setAmount(0);

        $icls2 = new IncomeClassification();
        $icls2->setClassificationType(IncomeClassificationType::E3_106);
        $icls2->setClassificationCategory(IncomeClassificationCategory::CATEGORY_1_3);
        $icls2->setAmount(0);

        $ecls = new ExpensesClassification();
        $ecls->setClassificationType(null);
        $ecls->setClassificationCategory(ExpenseClassificationCategory::CATEGORY_2_1);
        $ecls->setAmount(0);

        $row = new InvoiceDetails();
        $row->addIncomeClassification($icls1);
        $row->addIncomeClassification($icls2);
        $row->addExpensesClassification($ecls);

        $invoice = new Invoice();
        $invoice->addInvoiceDetails($row);
        
        $classifications = $this->toXML($invoice)->InvoicesDoc->invoice->invoiceDetails;

        $income = $classifications->incomeClassification;

        $this->assertCount(2, $income);
        $this->assertCount(2, $income[0]);

        $this->assertEquals(IncomeClassificationCategory::CATEGORY_3->value, $income[0]->get('icls:classificationCategory'));
        $this->assertEquals(0, $income[0]->get('icls:amount'));
        $this->assertArrayNotHasKey('icls:classificationType', $income[0]);

        $this->assertEquals(IncomeClassificationCategory::CATEGORY_1_3->value, $income[1]->get('icls:classificationCategory'));
        $this->assertEquals(0, $income[1]->get('icls:amount'));
        $this->assertEquals(IncomeClassificationType::E3_106->value, $income[1]->get('icls:classificationType'));

        $expenses = $classifications->expensesClassification;
        $this->assertCount(2, $expenses);

        $this->assertEquals(ExpenseClassificationCategory::CATEGORY_2_1->value, $expenses->get('ecls:classificationCategory'));
        $this->assertEquals(0, $expenses->get('ecls:amount'));
        $this->assertArrayNotHasKey('ecls:classificationType', $expenses);
    }
}