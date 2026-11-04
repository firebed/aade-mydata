# RequestBookInfo

`RequestBookInfo` is the shared base of two requests. They take the same parameters and return the same result:

| Request | Returns |
|---|---|
| [RequestMyIncome](request-my-income) | Your income for a date range |
| [RequestMyExpenses](request-my-expenses) | Your expenses for a date range |

`RequestBookInfo` is abstract. Use one of the two requests above.

```php
use Firebed\AadeMyData\Http\RequestMyIncome;

$result = (new RequestMyIncome())->handle(dateFrom: '01/01/2026', dateTo: '31/01/2026');

foreach ($result->getBookInfo() ?? [] as $row) {
    echo $row->getCounterVatNumber() . ' ' . $row->getGrossValue() . PHP_EOL;
}
```

## Parameters

| Parameter | Required | Description |
|---|---|---|
| `dateFrom` | Yes | Start of the issue date range, `dd/MM/yyyy` |
| `dateTo` | Yes | End of the issue date range, `dd/MM/yyyy` |
| `counterVatNumber` | No | VAT number of the counterpart |
| `entityVatNumber` | No | VAT number to search for. Default: the VAT number of the caller |
| `invType` | No | Invoice type, an `InvoiceType` case or its code (see [invoice types](../appendix/invoice-types)) |
| `nextPartitionKey` | No | Pagination key from the previous page |
| `nextRowKey` | No | Pagination key from the previous page |

A parameter you leave out matches every value.

## Results

`handle()` returns a `RequestedBookInfo`:

| Method | Returns |
|---|---|
| `getBookInfo()` | `BookInfo[]\|null`, the rows |
| `getContinuationToken()` | `ContinuationToken\|null`, set when there are more pages |

Each [BookInfo](../models/book-info-type) row sums the invoices that share the same counterpart VAT number,
issue date, invoice type and entity VAT number.

- `getMinMark()` / `getMaxMark()` are the lowest and highest MARK of the invoices in the row.
- `getCount()` is the number of those invoices.
- Self-billed invoices (`getSelfPricing()`) and type 1.5 invoices (`getInvoiceDetailType()`) come in separate rows.

```php
foreach ($result->getBookInfo() ?? [] as $row) {
    $row->getCounterVatNumber();
    $row->getIssueDate();
    $row->getInvType();          // InvoiceType|null
    $row->getNetValue();
    $row->getVatAmount();
    $row->getWithheldAmount();
    $row->getOtherTaxesAmount();
    $row->getStampDutyAmount();
    $row->getFeesAmount();
    $row->getDeductionsAmount();
    $row->getThirdPartyAmount();
    $row->getGrossValue();
    $row->getCount();
}
```

Amounts and counts come back as strings, as myDATA sends them.

## Pagination

`pages()` takes the same parameters as `handle()`, follows the continuation token and yields each page.
Pages are fetched only as you iterate.

```php
use Firebed\AadeMyData\Http\RequestMyExpenses;

foreach ((new RequestMyExpenses())->pages(dateFrom: '01/01/2026', dateTo: '31/12/2026') as $page) {
    foreach ($page->getBookInfo() ?? [] as $row) {
        // ...
    }
}
```

To resume from a saved token, pass its keys as `nextPartitionKey` / `nextRowKey` to `pages()` or `handle()`.
