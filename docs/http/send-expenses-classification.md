# SendExpensesClassification

```shell
# production
https://mydatapi.aade.gr/myDATA/SendExpensesClassification

# development
https://mydataapidev.aade.gr/SendExpensesClassification
```

Sends expense classifications for invoices already in myDATA, usually invoices you received.
You classify each invoice line, or the whole invoice with `postPerInvoice`.
You can also reject an invoice, see [Reject or deviation](#reject-or-deviation).

```php
use Firebed\AadeMyData\Enums\ExpenseClassificationCategory;
use Firebed\AadeMyData\Enums\ExpenseClassificationType;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\SendExpensesClassification;
use Firebed\AadeMyData\Models\ExpensesClassification;
use Firebed\AadeMyData\Models\InvoiceExpensesClassification;
use Firebed\AadeMyData\Models\InvoicesExpensesClassificationDetail;

$classification = (new ExpensesClassification())
    ->setClassificationType(ExpenseClassificationType::E3_102_001)
    ->setClassificationCategory(ExpenseClassificationCategory::CATEGORY_2_1)
    ->setAmount(100);

$line = (new InvoicesExpensesClassificationDetail())
    ->setLineNumber(1)
    ->addExpensesClassificationDetailData($classification);

$invoiceClassification = (new InvoiceExpensesClassification())
    ->setInvoiceMark(400001234567890)
    ->addInvoicesExpensesClassificationDetails($line);

try {
    $responses = (new SendExpensesClassification())->handle([$invoiceClassification]);
} catch (MyDataException $e) {
    echo $e->getMessage();
}
```

With the `MyData` client:

```php
use Firebed\AadeMyData\MyData;

$mydata = MyData::connect($userId, $subscriptionKey, 'dev');

$responses = $mydata->sendExpensesClassification([$invoiceClassification]);
```

## Parameters

| Parameter | Required | Description |
|---|---|---|
| `expensesClassifications` | Yes | A list of `InvoiceExpensesClassification`, or an `ExpensesClassificationsDoc` |
| `postPerInvoice` | No | `true` classifies the whole invoice, not each line. Default `false` |

> [!NOTE]
> Unlike `SendIncomeClassification`, `handle()` does not take a single `InvoiceExpensesClassification`.
> Wrap it in an array.

```php
use Firebed\AadeMyData\Models\ExpensesClassificationsDoc;

$doc = new ExpensesClassificationsDoc([$classification1, $classification2]);
$responses = (new SendExpensesClassification())->handle($doc);
```

## Models

All classes are in `Firebed\AadeMyData\Models`.

### InvoiceExpensesClassification

The classifications of one invoice.

| Field | Required | Description |
|---|---|---|
| `invoiceMark` | Yes | MARK of the invoice |
| `entityVatNumber` | No | VAT number of the entity. Send it only when a third party (e.g. an accountant) calls |
| `transactionMode` | Yes (choice) | `TransactionMode::REJECT` (1) or `TransactionMode::DEVIATION` (2) |
| `invoicesExpensesClassificationDetails` | Yes (choice) | One `InvoicesExpensesClassificationDetail` per invoice line |

Send either `transactionMode` or `invoicesExpensesClassificationDetails`, not both.
myDATA fills in `classificationMark` and `classificationPostMode` (0: per line, 1: per invoice).

### InvoicesExpensesClassificationDetail

The classifications of one invoice line.

| Field | Required | Description |
|---|---|---|
| `lineNumber` | Yes | Line number of the line in the invoice with that MARK |
| `expensesClassificationDetailData` | Yes | One or more [ExpensesClassification](../models/expenses-classification-type) |

`ExpensesClassification` uses `vatAmount`, `vatCategory` and `vatExemptionCategory` only for VAT classifications
(`VAT_...` types). `classificationCategory` is used only for E3 classifications.

For the allowed codes see [expense classifications](../appendix/expenses-classifications)
and [classification combinations](../classifications).

## Classify per invoice

With `postPerInvoice: true` you classify the whole invoice, not each line:

- `lineNumber` is ignored. It is mandatory, so give it any value.
- Send two `ExpensesClassification` items for the whole invoice: one E3 and one VAT classification
  (AADE allows more in some cases).
- The sum of the E3 `amount` values must equal the net value plus other taxes, stamp duty and fees of the invoice.
- Each `vatCategory` / `vatExemptionCategory` pair of the invoice needs a VAT classification with the same pair,
  and its `amount` / `vatAmount` must match the invoice totals for that pair.
- Not for fuel invoices or type 1.5 invoices.

```php
use Firebed\AadeMyData\Enums\VatCategory;

$e3 = (new ExpensesClassification())
    ->setClassificationType(ExpenseClassificationType::E3_102_001)
    ->setClassificationCategory(ExpenseClassificationCategory::CATEGORY_2_1)
    ->setAmount(100);

$vat = (new ExpensesClassification())
    ->setClassificationType(ExpenseClassificationType::VAT_361)
    ->setAmount(100)
    ->setVatAmount(24)
    ->setVatCategory(VatCategory::RATE_24);

$invoice = (new InvoiceExpensesClassification())
    ->setInvoiceMark(400001234567890)
    ->addInvoicesExpensesClassificationDetails(
        (new InvoicesExpensesClassificationDetail())
            ->setLineNumber(1)
            ->addExpensesClassificationDetailData($e3)
            ->addExpensesClassificationDetailData($vat)
    );

$responses = (new SendExpensesClassification())->handle([$invoice], postPerInvoice: true);
```

All the rules are in the AADE
[postPerInvoice guidelines](https://www.aade.gr/sites/default/files/2023-07/SendExpensesClassificationPostPerInvoiceGuidelines.pdf) (Greek).

## Reject or deviation

Instead of classifying, the recipient can reject the invoice. Set `transactionMode` and leave out the line classifications:

| Case | Value | Meaning |
|---|---|---|
| `TransactionMode::REJECT` | 1 | Reject the invoice, you disagree with it |
| `TransactionMode::DEVIATION` | 2 | Reject the invoice, the amounts deviate |

```php
use Firebed\AadeMyData\Enums\TransactionMode;

$rejection = (new InvoiceExpensesClassification())
    ->setInvoiceMark(400001234567890)
    ->setTransactionMode(TransactionMode::DEVIATION);

$responses = (new SendExpensesClassification())->handle([$rejection]);
```

The issuer sees the rejection in [RequestDocs](request-docs).

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
