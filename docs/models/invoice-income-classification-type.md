# InvoiceIncomeClassification

`InvoiceIncomeClassification` classifies the income of an invoice that is already in myDATA. You send it with
`SendIncomeClassification`. `RequestDocs` also returns it, in `RequestedDoc::getIncomeClassifications()`.

```php
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Http\SendIncomeClassification;
use Firebed\AadeMyData\Models\IncomeClassification;
use Firebed\AadeMyData\Models\InvoiceIncomeClassification;
use Firebed\AadeMyData\Models\InvoicesIncomeClassificationDetail;

$line = new InvoicesIncomeClassificationDetail();
$line->setLineNumber(1);
$line->addIncomeClassificationDetailData(new IncomeClassification([
    'classificationType' => IncomeClassificationType::E3_561_001,
    'classificationCategory' => IncomeClassificationCategory::CATEGORY_1_1,
    'amount' => 100,
]));

$classification = new InvoiceIncomeClassification();
$classification->setInvoiceMark(400001234567890);
$classification->addInvoicesIncomeClassificationDetails($line);

$responses = (new SendIncomeClassification())->handle($classification);
```

| Field                               | Type                                                                   | Required | Description |
|-------------------------------------|------------------------------------------------------------------------|----------|-------------|
| invoiceMark                         | `int`                                                                  | Yes      | MARK of the invoice |
| classificationMark                  | `int`                                                                  | No       | MARK of the classification. Set by myDATA, getter only |
| entityVatNumber                     | `string`                                                               | No       | VAT number of the entity. Only when a third party (e.g. an accountant) sends it |
| transactionMode                     | `TransactionMode`                                                      | No       | `REJECT` (1): reject the invoice. `DEVIATION` (2): the amounts differ |
| invoicesIncomeClassificationDetails | `InvoicesIncomeClassificationDetail[]`                                 | No       | Classifications per invoice row. Add one with `addInvoicesIncomeClassificationDetails()` |

Send either `transactionMode` or `invoicesIncomeClassificationDetails`, not both.

`InvoicesIncomeClassificationDetail` fields:

| Field                          | Type                                                    | Required | Description |
|--------------------------------|---------------------------------------------------------|----------|-------------|
| lineNumber                     | `int`                                                   | Yes      | Row number of the original invoice |
| incomeClassificationDetailData | [`IncomeClassification[]`](income-classification-type)  | Yes      | Classifications of the row. Add one with `addIncomeClassificationDetailData()` |

> [!NOTE]
> `handle($classifications, postPerInvoice: true)` sends the classifications per invoice instead of per row.
