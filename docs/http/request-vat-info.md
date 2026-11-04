# RequestVatInfo

```shell
# production
https://mydatapi.aade.gr/myDATA/RequestVatInfo

# development
https://mydataapidev.aade.gr/RequestVatInfo
```

Returns the VAT data (inputs and outputs) of a VAT number for a date range, per invoice or grouped per day.

```php
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\RequestVatInfo;

try {
    $result = (new RequestVatInfo())->handle(dateFrom: '01/01/2026', dateTo: '31/01/2026');

    foreach ($result->getVatInfo() ?? [] as $info) {
        echo $info->getMark() . ': ' . $info->getVat301() . PHP_EOL;
    }
} catch (MyDataException $e) {
    echo $e->getMessage();
}
```

With the `MyData` client:

```php
use Firebed\AadeMyData\MyData;

$mydata = MyData::connect($userId, $subscriptionKey, 'dev');

$result = $mydata->requestVatInfo(dateFrom: '01/01/2026', dateTo: '31/01/2026');
```

## Parameters

| Parameter | Required | Description |
|---|---|---|
| `dateFrom` | Yes | Start of the issue date range, `dd/MM/yyyy` |
| `dateTo` | Yes | End of the issue date range, `dd/MM/yyyy` |
| `entityVatNumber` | No | VAT number to search for. Default: the VAT number of the caller |
| `groupedPerDay` | No | `true` groups the results per day. Default `false` |
| `nextPartitionKey` | No | Pagination key from the previous page, when `groupedPerDay` is `false` |
| `nextRowKey` | No | Pagination key from the previous page, when `groupedPerDay` is `false` |

## Results

`handle()` returns a `RequestedVatInfo`:

| Method | Returns |
|---|---|
| `getVatInfo()` | `VatInfo[]\|null`, see [VatInfo](../models/vat-info-type) |
| `getContinuationToken()` | `ContinuationToken\|null`, set when there are more pages |

Each `VatInfo` holds the VAT amount of each field of the VAT return (`getVat301()`, `getVat302()`, …).

```php
$result = (new RequestVatInfo())->handle(
    dateFrom: '01/01/2026',
    dateTo: '31/01/2026',
    groupedPerDay: true,
);
```

## Pagination

`pages()` takes the same parameters as `handle()`, follows the continuation token and yields each page.
myDATA pages the results only when `groupedPerDay` is `false`.

```php
foreach ((new RequestVatInfo())->pages(dateFrom: '01/01/2026', dateTo: '31/12/2026') as $page) {
    foreach ($page->getVatInfo() ?? [] as $info) {
        // ...
    }
}
```
