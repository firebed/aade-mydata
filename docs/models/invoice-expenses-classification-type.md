# InvoiceExpensesClassification

`InvoiceExpensesClassification` classifies the expenses of an invoice that is already in myDATA. You send it with
`SendExpensesClassification`. `RequestDocs` also returns it, in `RequestedDoc::getExpensesClassifications()`.

```php
use Firebed\AadeMyData\Enums\ExpenseClassificationCategory;
use Firebed\AadeMyData\Enums\ExpenseClassificationType;
use Firebed\AadeMyData\Http\SendExpensesClassification;
use Firebed\AadeMyData\Models\ExpensesClassification;
use Firebed\AadeMyData\Models\InvoiceExpensesClassification;
use Firebed\AadeMyData\Models\InvoicesExpensesClassificationDetail;

$line = new InvoicesExpensesClassificationDetail();
$line->setLineNumber(1);
$line->addExpensesClassificationDetailData(new ExpensesClassification([
    'classificationType' => ExpenseClassificationType::E3_102_001,
    'classificationCategory' => ExpenseClassificationCategory::CATEGORY_2_1,
    'amount' => 100,
]));

$classification = new InvoiceExpensesClassification();
$classification->setInvoiceMark(400001234567890);
$classification->addInvoicesExpensesClassificationDetails($line);

$responses = (new SendExpensesClassification())->handle([$classification]);
```

| Field                                 | Type                                     | Required | Description |
|---------------------------------------|------------------------------------------|----------|-------------|
| invoiceMark                           | `int`                                    | Yes      | MARK of the invoice |
| classificationMark                    | `int`                                    | No       | MARK of the classification. Set by myDATA, getter only |
| entityVatNumber                       | `string`                                 | No       | VAT number of the entity. Only when a third party (e.g. an accountant) sends it |
| transactionMode                       | `TransactionMode`                        | No       | `REJECT` (1): reject the invoice. `DEVIATION` (2): the amounts differ |
| invoicesExpensesClassificationDetails | `InvoicesExpensesClassificationDetail[]` | No       | Classifications per invoice row. Add one with `addInvoicesExpensesClassificationDetails()` |
| classificationPostMode                | `int`                                    | No       | Read only. `0`: sent per row, `1`: sent per invoice |

Send either `transactionMode` or `invoicesExpensesClassificationDetails`, not both.

`InvoicesExpensesClassificationDetail` fields:

| Field                            | Type                                                        | Required | Description |
|----------------------------------|-------------------------------------------------------------|----------|-------------|
| lineNumber                       | `int`                                                       | Yes      | Row number of the original invoice |
| expensesClassificationDetailData | [`ExpensesClassification[]`](expenses-classification-type)  | Yes      | Classifications of the row. Add one with `addExpensesClassificationDetailData()` |

> [!NOTE]
> `handle($classifications, postPerInvoice: true)` sends the classifications per invoice instead of per row.
> `SendExpensesClassification::handle()` takes an `ExpensesClassificationsDoc` or an array, not a single model.
