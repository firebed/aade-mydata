# Invoice variation types

Marks an invoice sent because of an omission or a deviation (`invoiceVariationType`). Enum: `InvoiceVariationType`.

```php
use Firebed\AadeMyData\Enums\InvoiceVariationType;
use Firebed\AadeMyData\Models\InvoiceHeader;

$header = (new InvoiceHeader())->setInvoiceVariationType(InvoiceVariationType::OMISSION_BY_RECIPIENT);
```

| Code | Enum | `label()` | English |
|---|---|---|---|
| 1 | `InvoiceVariationType::OMISSION_BY_RECIPIENT` | Διαβίβαση Παράλειψης από Λήπτη | Omission, transmitted by the recipient |
| 2 | `InvoiceVariationType::OMISSION_BY_ISSUER` | Διαβίβαση Παράλειψης από Εκδότη | Omission, transmitted by the issuer |
| 3 | `InvoiceVariationType::DEVIATION_BY_RECIPIENT` | Διαβίβαση Απόκλισης από Λήπτη | Deviation, transmitted by the recipient |
| 4 | `InvoiceVariationType::DEVIATION_BY_ISSUER` | Διαβίβαση Απόκλισης από Εκδότη | Deviation, transmitted by the issuer |

| Code | When | Allowed invoice types |
|---|---|---|
| 1 | The recipient sends the data because the issuer did not. | 1.1, 1.6, 2.1, 2.4, 5.2, 8.1, 8.2 |
| 2 | The issuer sends the data after it agrees with the recipient's omission flag. | 11.3, 11.4, 13.1, 13.31 |
| 3 | The recipient sends the data (non-matching invoices) because the issuer's data deviate. | 11.3, 11.4, 13.1, 13.31. For invoices issued in 2021 only: 1.1, 5.2 |
| 4 | The issuer sends the data after it agrees with the recipient's "deviation" flag on the matching A1 invoice it sent. | 11.3, 11.4, 13.1, 13.31 |
