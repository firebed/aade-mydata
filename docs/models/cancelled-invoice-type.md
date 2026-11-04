# CancelledInvoice

`CancelledInvoice` is one cancellation in a [RequestedDoc](requested-doc-type).

```php
foreach ($doc->getCancelledInvoices() ?? [] as $cancelled) {
    $cancelled->getInvoiceMark();      // MARK of the cancelled invoice
    $cancelled->getCancellationMark(); // MARK of the cancellation
    $cancelled->getCancellationDate(); // Y-m-d
}
```

| Field            | Getter                  | Type     | Description                    |
|------------------|-------------------------|----------|--------------------------------|
| invoiceMark      | `getInvoiceMark()`      | `string` | MARK of the cancelled invoice  |
| cancellationMark | `getCancellationMark()` | `string` | MARK of the cancellation       |
| cancellationDate | `getCancellationDate()` | `string` | Cancellation date (`Y-m-d`)    |
