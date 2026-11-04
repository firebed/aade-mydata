# RequestMyExpenses

```shell
# production
https://mydatapi.aade.gr/myDATA/RequestMyExpenses

# development
https://mydataapidev.aade.gr/RequestMyExpenses
```

Returns rows with your expenses for a closed date range. You can filter by counterpart VAT number and invoice type.
It shares its parameters and result with [RequestMyIncome](request-my-income), see [RequestBookInfo](request-book-info).

```php
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\RequestMyExpenses;

try {
    $result = (new RequestMyExpenses())->handle(dateFrom: '01/01/2026', dateTo: '31/12/2026');

    foreach ($result->getBookInfo() ?? [] as $row) {
        echo $row->getCounterVatNumber() . PHP_EOL;
        echo $row->getIssueDate() . PHP_EOL;
        echo $row->getNetValue() . PHP_EOL;
    }
} catch (MyDataException $e) {
    echo $e->getMessage();
}
```

With the `MyData` client:

```php
use Firebed\AadeMyData\MyData;

$mydata = MyData::connect($userId, $subscriptionKey, 'dev');

$result = $mydata->requestMyExpenses(dateFrom: '01/01/2026', dateTo: '31/12/2026');
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

`handle()` returns a `RequestedBookInfo`. `getBookInfo()` gives the rows as [BookInfo](../models/book-info-type) objects.
See [RequestBookInfo](request-book-info#results) for the row fields.

## Pagination

```php
foreach ((new RequestMyExpenses())->pages(dateFrom: '01/01/2026', dateTo: '31/12/2026') as $page) {
    foreach ($page->getBookInfo() ?? [] as $row) {
        // ...
    }
}
```
