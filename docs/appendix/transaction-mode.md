# Transaction modes

Rejects an invoice when you send classifications (`transactionMode`). Enum: `TransactionMode`. Used in `InvoiceIncomeClassification` and `InvoiceExpensesClassification`.

```php
use Firebed\AadeMyData\Enums\TransactionMode;
use Firebed\AadeMyData\Models\InvoiceExpensesClassification;

$classification = (new InvoiceExpensesClassification())
    ->setInvoiceMark(400001234567890)
    ->setTransactionMode(TransactionMode::REJECT);
```

Send either a transaction mode or classification details, not both. See [SendExpensesClassification](../http/send-expenses-classification) and [SendIncomeClassification](../http/send-income-classification).

| Code | Enum | `label()` | English |
|---|---|---|---|
| 0 | `TransactionMode::ACCEPT` | Αποδοχή του παραστατικού | Accept the invoice |
| 1 | `TransactionMode::REJECT` | Απόρριψη του παραστατικού λόγω διαφωνίας | Reject the invoice (disagreement) |
| 2 | `TransactionMode::DEVIATION` | Απόρριψη του παραστατικού λόγω απόκλισης στα ποσά | Reject the invoice (amounts deviate) |

> [!NOTE]
> myDATA accepts only `1` and `2`. `ACCEPT` (`0`) is not a myDATA value.
