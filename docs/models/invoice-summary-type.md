# InvoiceSummary

`InvoiceSummary` holds the totals of the invoice. You can let the library build it from the rows:

```php
$invoice->summarizeInvoice();
```

Or build it yourself:

```php
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Models\InvoiceSummary;

$summary = new InvoiceSummary();
$summary->setTotalNetValue(50);
$summary->setTotalVatAmount(12);
$summary->setTotalWithheldAmount(6);
$summary->setTotalFeesAmount(0);
$summary->setTotalStampDutyAmount(0);
$summary->setTotalOtherTaxesAmount(0);
$summary->setTotalDeductionsAmount(0);
$summary->setTotalGrossValue(56); // 50 + 12 - 6
$summary->addIncomeClassification(
    IncomeClassificationType::E3_561_001,
    IncomeClassificationCategory::CATEGORY_1_1,
    50,
);

$invoice->setInvoiceSummary($summary);
```

| Field                  | Type                                                         | Required | Description |
|------------------------|--------------------------------------------------------------|----------|-------------|
| totalNetValue          | `float`                                                      | Yes      | Total net value |
| totalVatAmount         | `float`                                                      | Yes      | Total VAT |
| totalWithheldAmount    | `float`                                                      | Yes      | Total withholding tax |
| totalFeesAmount        | `float`                                                      | Yes      | Total fees |
| totalStampDutyAmount   | `float`                                                      | Yes      | Total stamp duty |
| totalOtherTaxesAmount  | `float`                                                      | Yes      | Total other taxes |
| totalDeductionsAmount  | `float`                                                      | Yes      | Total deductions |
| totalGrossValue        | `float`                                                      | Yes      | Total gross value |
| incomeClassification   | [`IncomeClassification[]`](income-classification-type)       | No       | Income classification sums. Methods `setIncomeClassifications()`, `getIncomeClassifications()`, `addIncomeClassification()` |
| expensesClassification | [`ExpensesClassification[]`](expenses-classification-type)   | No       | Expense classification sums. Methods `setExpensesClassifications()`, `getExpensesClassifications()`, `addExpensesClassification()` |

## Notes

- All amounts are 0 or more, with up to two decimals.
- The classifications hold one sum per `classificationType` and `classificationCategory` pair found in the rows.
- The tax totals (except `totalVatAmount`) are the sums of the row taxes, or of the [taxesTotals](tax-totals-type) of the invoice.
