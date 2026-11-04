# Invoice detail types

Marking of an invoice row (`invoiceDetailType`). Enum: `InvoiceDetailType`.

```php
use Firebed\AadeMyData\Enums\InvoiceDetailType;
use Firebed\AadeMyData\Models\InvoiceDetails;

$row = (new InvoiceDetails())->setInvoiceDetailType(InvoiceDetailType::THIRD_PARTY_SALES_FEE);
```

| Code | Enum | `label()` | English |
|---|---|---|---|
| 1 | `InvoiceDetailType::THIRD_PARTY_SALES_SETTLEMENT` | Εκκαθάριση Πωλήσεων Τρίτων | Third-party sales settlement |
| 2 | `InvoiceDetailType::THIRD_PARTY_SALES_FEE` | Αμοιβή από Πωλήσεις Τρίτων | Fee from third-party sales |
