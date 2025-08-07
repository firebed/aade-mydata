<?php

namespace Tests;

use Firebed\AadeMyData\Enums\ExpenseClassificationCategory;
use Firebed\AadeMyData\Enums\ExpenseClassificationType;
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Services\Classifications;
use PHPUnit\Framework\TestCase;

class ClassificationTest extends TestCase
{
    private array $incomeClassifications;
    private array $expenseClassifications;

    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->incomeClassifications = require __DIR__.'/../config/income-classifications.php';
        $this->expenseClassifications = require __DIR__.'/../config/expense-classifications.php';
    }

    public function test_income_classification_categories_array()
    {
        $expected = $this->incomeClassifications['1.1'];
        $actual_1 = InvoiceType::TYPE_1_1->incomeClassifications()->toArray();
        $actual_2 = IncomeClassificationType::for(InvoiceType::TYPE_1_1)->toArray();
        $actual_3 = Classifications::incomeClassifications(InvoiceType::TYPE_1_1)->toArray();

        $this->assertEquals($expected, $actual_1);
        $this->assertEquals($expected, $actual_2);
        $this->assertEquals($expected, $actual_3);
    }

    public function test_income_classification_categories_label()
    {
        $expected = array_keys($this->incomeClassifications['1.1']);
        $actual_1 = InvoiceType::TYPE_1_1->incomeClassifications()->toKeyLabel();
        $actual_2 = IncomeClassificationType::for(InvoiceType::TYPE_1_1)->toKeyLabel();
        $actual_3 = Classifications::incomeClassifications(InvoiceType::TYPE_1_1)->toKeyLabel();

        foreach ($expected as $key) {
            $category = IncomeClassificationCategory::from($key);
            $this->assertEquals($category->label(), $actual_1[$key]);
            $this->assertEquals($category->label(), $actual_2[$key]);
            $this->assertEquals($category->label(), $actual_3[$key]);
        }
    }

    public function test_income_classification_contains_type()
    {
        $classifications = InvoiceType::TYPE_1_1->incomeClassifications();

        $this->assertTrue($classifications->contains(IncomeClassificationCategory::CATEGORY_1_1));
        $this->assertTrue($classifications->get(IncomeClassificationCategory::CATEGORY_1_1)->contains(IncomeClassificationType::E3_561_001));
        $this->assertTrue($classifications->get(IncomeClassificationCategory::CATEGORY_1_95)->contains(null));

        $this->assertFalse($classifications->contains(IncomeClassificationCategory::CATEGORY_1_6));
        $this->assertFalse($classifications->get(IncomeClassificationCategory::CATEGORY_1_1)->contains(IncomeClassificationType::E3_596));
    }

    public function test_income_type_classifications_contain_type()
    {
        $types = InvoiceType::TYPE_1_1->incomeClassifications(IncomeClassificationCategory::CATEGORY_1_1);
        $this->assertTrue($types->contains(IncomeClassificationType::E3_561_001));

        $types = InvoiceType::TYPE_1_1->incomeClassifications(IncomeClassificationCategory::CATEGORY_1_6);
        $this->assertFalse($types->contains(IncomeClassificationType::E3_596));
    }

    public function test_income_classification_types_array()
    {
        $expected = $this->incomeClassifications['1.1']['category1_1'];
        $actual_1 = InvoiceType::TYPE_1_1->incomeClassifications()->get(IncomeClassificationCategory::CATEGORY_1_1)->toArray();
        $actual_2 = IncomeClassificationType::for(InvoiceType::TYPE_1_1, IncomeClassificationCategory::CATEGORY_1_1)->toArray();
        $actual_3 = Classifications::incomeClassifications(InvoiceType::TYPE_1_1, IncomeClassificationCategory::CATEGORY_1_1)->toArray();

        $this->assertEquals($expected, $actual_1);
        $this->assertEquals($expected, $actual_2);
        $this->assertEquals($expected, $actual_3);
    }

    public function test_income_classification_types_label()
    {
        $expected = $this->incomeClassifications['1.1']['category1_1'];
        $actual_1 = InvoiceType::TYPE_1_1->incomeClassifications()->get(IncomeClassificationCategory::CATEGORY_1_1)->toKeyLabel();
        $actual_2 = IncomeClassificationType::for(InvoiceType::TYPE_1_1, IncomeClassificationCategory::CATEGORY_1_1)->toKeyLabel();
        $actual_3 = Classifications::incomeClassifications(InvoiceType::TYPE_1_1, IncomeClassificationCategory::CATEGORY_1_1)->toKeyLabel();

        foreach ($expected as $key) {
            if ($key !== '') {
                $type = IncomeClassificationType::from($key);
                $this->assertEquals($type->label(), $actual_1[$key]);
                $this->assertEquals($type->label(), $actual_2[$key]);
                $this->assertEquals($type->label(), $actual_3[$key]);
            }
        }
    }

    public function test_income_classification_labels()
    {
        $expected = $this->incomeClassifications['1.1'];
        $actual_1 = InvoiceType::TYPE_1_1->incomeClassifications()->toKeyLabels();
        $actual_2 = IncomeClassificationType::for(InvoiceType::TYPE_1_1)->toKeyLabels();
        $actual_3 = Classifications::incomeClassifications(InvoiceType::TYPE_1_1)->toKeyLabels();

        foreach ($expected as $categoryKey => $categories) {
            foreach ($categories as $typeKey) {
                if ($typeKey !== null) {
                    $type = IncomeClassificationType::from($typeKey);
                    $this->assertEquals($type->label(), $actual_1[$categoryKey][$typeKey]);
                    $this->assertEquals($type->label(), $actual_2[$categoryKey][$typeKey]);
                    $this->assertEquals($type->label(), $actual_3[$categoryKey][$typeKey]);
                }
            }
        }
    }

    public function test_expense_classification_categories_array()
    {
        $expected = $this->expenseClassifications['1.1'];
        $actual_1 = InvoiceType::TYPE_1_1->expenseClassifications()->toArray();
        $actual_2 = ExpenseClassificationType::for(InvoiceType::TYPE_1_1)->toArray();
        $actual_3 = Classifications::expenseClassifications(InvoiceType::TYPE_1_1)->toArray();

        $this->assertEquals($expected, $actual_1);
        $this->assertEquals($expected, $actual_2);
        $this->assertEquals($expected, $actual_3);
    }

    public function test_expense_classification_categories_label()
    {
        $expected = array_keys($this->expenseClassifications['1.1']);
        $actual_1 = InvoiceType::TYPE_1_1->expenseClassifications()->toKeyLabel();
        $actual_2 = ExpenseClassificationType::for(InvoiceType::TYPE_1_1)->toKeyLabel();
        $actual_3 = Classifications::expenseClassifications(InvoiceType::TYPE_1_1)->toKeyLabel();

        foreach ($expected as $key) {
            $category = ExpenseClassificationCategory::from($key);
            $this->assertEquals($category->label(), $actual_1[$key]);
            $this->assertEquals($category->label(), $actual_2[$key]);
            $this->assertEquals($category->label(), $actual_3[$key]);
        }
    }

    public function test_expense_classification_types_array()
    {
        $expected = $this->expenseClassifications['1.1']['category2_1'];
        $actual_1 = InvoiceType::TYPE_1_1->expenseClassifications()->get(ExpenseClassificationCategory::CATEGORY_2_1)->toArray();
        $actual_2 = ExpenseClassificationType::for(InvoiceType::TYPE_1_1, ExpenseClassificationCategory::CATEGORY_2_1)->toArray();
        $actual_3 = Classifications::expenseClassifications(InvoiceType::TYPE_1_1, ExpenseClassificationCategory::CATEGORY_2_1)->toArray();

        $this->assertEquals($expected, $actual_1);
        $this->assertEquals($expected, $actual_2);
        $this->assertEquals($expected, $actual_3);
    }

    public function test_expense_classification_types_label()
    {
        $expected = $this->expenseClassifications['1.1']['category2_1'];
        $actual_1 = InvoiceType::TYPE_1_1->expenseClassifications()->get(ExpenseClassificationCategory::CATEGORY_2_1)->toKeyLabel();
        $actual_2 = ExpenseClassificationType::for(InvoiceType::TYPE_1_1, ExpenseClassificationCategory::CATEGORY_2_1)->toKeyLabel();
        $actual_3 = Classifications::expenseClassifications(InvoiceType::TYPE_1_1, ExpenseClassificationCategory::CATEGORY_2_1)->toKeyLabel();

        foreach ($expected as $key) {
            if ($key !== '') {
                $type = ExpenseClassificationType::from($key);
                $this->assertEquals($type->label(), $actual_1[$key]);
                $this->assertEquals($type->label(), $actual_2[$key]);
                $this->assertEquals($type->label(), $actual_3[$key]);
            }
        }
    }

    public function test_expense_classification_labels()
    {
        $expected = $this->expenseClassifications['1.1'];
        $actual_1 = InvoiceType::TYPE_1_1->expenseClassifications()->toKeyLabels();
        $actual_2 = ExpenseClassificationType::for(InvoiceType::TYPE_1_1)->toKeyLabels();
        $actual_3 = Classifications::expenseClassifications(InvoiceType::TYPE_1_1)->toKeyLabels();

        foreach ($expected as $categoryKey => $categories) {
            foreach ($categories as $typeKey) {
                if ($typeKey !== '') {
                    $type = ExpenseClassificationType::from($typeKey);
                    $this->assertEquals($type->label(), $actual_1[$categoryKey][$typeKey]);
                    $this->assertEquals($type->label(), $actual_2[$categoryKey][$typeKey]);
                    $this->assertEquals($type->label(), $actual_3[$categoryKey][$typeKey]);
                }
            }
        }
    }

    public function test_expense_classification_contains_type()
    {
        $classifications = InvoiceType::TYPE_1_1->expenseClassifications();

        $this->assertTrue($classifications->contains(ExpenseClassificationCategory::CATEGORY_2_1));
        $this->assertTrue($classifications->get(ExpenseClassificationCategory::CATEGORY_2_1)->contains(ExpenseClassificationType::E3_102_001));

        $this->assertFalse($classifications->contains(ExpenseClassificationCategory::CATEGORY_2_6));
        $this->assertFalse($classifications->get(ExpenseClassificationCategory::CATEGORY_2_1)->contains(ExpenseClassificationType::E3_586));
    }

    public function test_expense_type_classifications_contain_type()
    {
        $types = InvoiceType::TYPE_1_1->expenseClassifications(ExpenseClassificationCategory::CATEGORY_2_1);
        $this->assertTrue($types->contains(ExpenseClassificationType::E3_102_001));

        $types = InvoiceType::TYPE_1_1->expenseClassifications(ExpenseClassificationCategory::CATEGORY_2_6);
        $this->assertFalse($types->contains(ExpenseClassificationType::E3_586));
    }

    public function test_classification_exists()
    {
        $this->assertTrue(Classifications::incomeClassificationExists('1.1', 'category1_1', 'E3_561_001'));
        $this->assertTrue(Classifications::incomeClassificationExists('1.1', 'category1_95'));
        $this->assertFalse(Classifications::incomeClassificationExists('1.1', 'category1_1', 'E3_205'));
        $this->assertFalse(Classifications::incomeClassificationExists('11.1', 'category1_1'));

        $this->assertTrue(Classifications::expenseClassificationExists('1.1', 'category2_1', 'E3_102_001'));
        $this->assertTrue(Classifications::expenseClassificationExists('1.1', 'category2_95'));
        $this->assertFalse(Classifications::expenseClassificationExists('1.1', 'category1_1', 'E3_205'));
    }
}