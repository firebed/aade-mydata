# IncomeClassification

`IncomeClassification` is one income classification. You add it to an invoice row (per row), to the
[InvoiceSummary](invoice-summary-type) (sums per type and category), or to an
[InvoiceIncomeClassification](invoice-income-classification-type) when you send classifications on their own.

```php
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Models\IncomeClassification;

$icls = new IncomeClassification();
$icls->setClassificationType(IncomeClassificationType::E3_561_001);
$icls->setClassificationCategory(IncomeClassificationCategory::CATEGORY_1_1);
$icls->setAmount(45);

$row->addIncomeClassification($icls);
```

| Field                  | Type                                                                        | Required | Description                         |
|------------------------|-----------------------------------------------------------------------------|----------|-------------------------------------|
| classificationType     | [`IncomeClassificationType`](../appendix/income-classifications)            | No       | Classification code                 |
| classificationCategory | [`IncomeClassificationCategory`](../appendix/income-classifications)        | Yes      | Classification category             |
| amount                 | `float`                                                                     | Yes      | Amount                              |
| id                     | `int`                                                                       | No       | Serial number of the classification |

`id` numbers the classifications inside one row (1, 2, 3, ...).

`addAmount(float)` adds to the current amount.
