# ExpensesClassification

`ExpensesClassification` is one expense classification. You add it to an invoice row (per row), to the
[InvoiceSummary](invoice-summary-type) (sums per type and category), or to an
[InvoiceExpensesClassification](invoice-expenses-classification-type) when you send classifications on their own.

```php
use Firebed\AadeMyData\Enums\ExpenseClassificationCategory;
use Firebed\AadeMyData\Enums\ExpenseClassificationType;
use Firebed\AadeMyData\Models\ExpensesClassification;

$ecls = new ExpensesClassification();
$ecls->setClassificationType(ExpenseClassificationType::E3_102_001);
$ecls->setClassificationCategory(ExpenseClassificationCategory::CATEGORY_2_1);
$ecls->setAmount(45);

$row->addExpensesClassification($ecls);
```

| Field                  | Type                                                                          | Required | Description                         |
|------------------------|-------------------------------------------------------------------------------|----------|-------------------------------------|
| classificationType     | [`ExpenseClassificationType`](../appendix/expenses-classifications)           | No       | Classification code                 |
| classificationCategory | [`ExpenseClassificationCategory`](../appendix/expenses-classifications)       | No       | Classification category             |
| amount                 | `float`                                                                       | Yes      | Amount                              |
| vatAmount              | `float`                                                                       | No       | VAT amount                          |
| vatCategory            | [`VatCategory`](../appendix/vat-categories)                                   | No       | VAT category                        |
| vatExemptionCategory   | [`VatExemption`](../appendix/vat-exemption-categories)                        | No       | VAT exemption category              |
| id                     | `int`                                                                         | No       | Serial number of the classification |

## Notes

- `id` numbers the classifications inside one row (1, 2, 3, ...).
- `vatAmount`, `vatCategory` and `vatExemptionCategory` apply only to VAT classifications (`VAT_...` types).
  myDATA ignores them otherwise.
- `classificationCategory` applies only to E3 classifications. myDATA ignores it otherwise.
- `addAmount(float)` and `addVatAmount(float)` add to the current amounts.
