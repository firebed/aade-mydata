# SendIncomeClassification

```shell
# production
https://mydatapi.aade.gr/myDATA/SendIncomeClassification

# development
https://mydataapidev.aade.gr/SendIncomeClassification
```

Sends income classifications for invoices already in myDATA, separately from the invoice.
Each classification points to an invoice by its MARK and to an invoice line by its line number.

```php
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\SendIncomeClassification;
use Firebed\AadeMyData\Models\IncomeClassification;
use Firebed\AadeMyData\Models\InvoiceIncomeClassification;
use Firebed\AadeMyData\Models\InvoicesIncomeClassificationDetail;

$classification = (new IncomeClassification())
    ->setClassificationType(IncomeClassificationType::E3_561_001)
    ->setClassificationCategory(IncomeClassificationCategory::CATEGORY_1_1)
    ->setAmount(100);

$line = (new InvoicesIncomeClassificationDetail())
    ->setLineNumber(1)
    ->addIncomeClassificationDetailData($classification);

$invoiceClassification = (new InvoiceIncomeClassification())
    ->setInvoiceMark(400001234567890)
    ->addInvoicesIncomeClassificationDetails($line);

try {
    $responses = (new SendIncomeClassification())->handle($invoiceClassification);
} catch (MyDataException $e) {
    echo $e->getMessage();
}
```

With the `MyData` client:

```php
use Firebed\AadeMyData\MyData;

$mydata = MyData::connect($userId, $subscriptionKey, 'dev');

$responses = $mydata->sendIncomeClassification($invoiceClassification);
```

## Parameters

| Parameter | Required | Description |
|---|---|---|
| `incomeClassifications` | Yes | An `InvoiceIncomeClassification`, a list of them, or an `IncomeClassificationsDoc` |
| `postPerInvoice` | No | Sends `postPerInvoice=true`. Default `false`. The AADE spec defines it only for [expense classifications](send-expenses-classification#classify-per-invoice) |

Send many invoices at once:

```php
use Firebed\AadeMyData\Models\IncomeClassificationsDoc;

$responses = (new SendIncomeClassification())->handle([$classification1, $classification2]);

// or
$doc = new IncomeClassificationsDoc([$classification1, $classification2]);
$responses = (new SendIncomeClassification())->handle($doc);
```

## Models

All classes are in `Firebed\AadeMyData\Models`.

### InvoiceIncomeClassification

The classifications of one invoice.

| Field | Required | Description |
|---|---|---|
| `invoiceMark` | Yes | MARK of the invoice |
| `entityVatNumber` | No | VAT number of the entity. Send it only when a third party (e.g. an accountant) calls |
| `transactionMode` | Yes (choice) | `TransactionMode::REJECT` (1) or `TransactionMode::DEVIATION` (2) |
| `invoicesIncomeClassificationDetails` | Yes (choice) | One `InvoicesIncomeClassificationDetail` per invoice line |

Send either `transactionMode` or `invoicesIncomeClassificationDetails`, not both.
myDATA fills in `classificationMark` and `classificationPostMode`.

### InvoicesIncomeClassificationDetail

The classifications of one invoice line.

| Field | Required | Description |
|---|---|---|
| `lineNumber` | Yes | Line number of the line in the invoice with that MARK |
| `incomeClassificationDetailData` | Yes | One or more [IncomeClassification](../models/income-classification-type) |

For the allowed type and category codes see [income classifications](../appendix/income-classifications)
and [classification combinations](../classifications).

## Reject or deviation

`transactionMode` replaces the line classifications:

| Case | Value | Meaning |
|---|---|---|
| `TransactionMode::REJECT` | 1 | Reject the invoice, you disagree with it |
| `TransactionMode::DEVIATION` | 2 | The amounts deviate |

```php
use Firebed\AadeMyData\Enums\TransactionMode;

$rejection = (new InvoiceIncomeClassification())
    ->setInvoiceMark(400001234567890)
    ->setTransactionMode(TransactionMode::REJECT);

$responses = (new SendIncomeClassification())->handle($rejection);
```

> [!WARNING]
> `TransactionMode::ACCEPT` (0) exists in the enum but the myDATA schema accepts only 1 and 2.

## Results

`handle()` returns a `ResponseDoc` with one [Response](../models/response-type) per invoice.

```php
foreach ($responses as $response) {
    if ($response->isSuccessful()) {
        $response->getInvoiceMark();        // MARK of the classified invoice
        $response->getClassificationMark(); // MARK of the classification
    } else {
        foreach ($response->getErrors() as $error) {
            echo $error->getCode() . ': ' . $error->getMessage() . PHP_EOL;
        }
    }
}
```
