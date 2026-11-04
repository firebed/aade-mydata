# RequestE3Info

```shell
# production
https://mydatapi.aade.gr/myDATA/RequestE3Info

# development
https://mydataapidev.aade.gr/RequestE3Info
```

Returns the E3 data of a VAT number for a date range, per invoice or grouped per day.
Each record holds a classification (E3 code) and its amount.

```php
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\RequestE3Info;

try {
    $result = (new RequestE3Info())->handle(dateFrom: '01/01/2026', dateTo: '31/01/2026');

    foreach ($result->getE3Info() ?? [] as $info) {
        echo $info->getMark() . ' ' . $info->getClassType() . ' ' . $info->getClassValue() . PHP_EOL;
    }
} catch (MyDataException $e) {
    echo $e->getMessage();
}
```

With the `MyData` client:

```php
use Firebed\AadeMyData\MyData;

$mydata = MyData::connect($userId, $subscriptionKey, 'dev');

$result = $mydata->requestE3Info(dateFrom: '01/01/2026', dateTo: '31/01/2026');
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

```php
$result = (new RequestE3Info())->handle(
    dateFrom: '01/01/2026',
    dateTo: '31/01/2026',
    entityVatNumber: '123456789',
    groupedPerDay: true,
);
```

## Results

`handle()` returns a `Firebed\AadeMyData\Models\RequestedE3Info`:

| Method | Returns |
|---|---|
| `getE3Info()` | `E3Info[]\|null`, one item per record |
| `getContinuationToken()` | `ContinuationToken\|null`, set when there are more pages |

### E3Info

`Firebed\AadeMyData\Models\E3Info` has these fields. Values are strings, as myDATA sends them, except `IsCancelled` (bool).

| Field | Method | Description |
|---|---|---|
| `V_Afm` | `getVat()` | VAT number |
| `V_Mark` | `getMark()` | MARK of the invoice |
| `IssueDate` | `getIssueDate()` | Issue date |
| `V_Class_Category` | `getClassCategory()` | Classification category, e.g. `category1_1` |
| `V_Class_Type` | `getClassType()` | Classification type (E3 code), e.g. `E3_561_001` |
| `V_Class_Value` | `getClassValue()` | Amount |
| `vBook` | `get('vBook')` | Not described in the AADE spec |
| `IsCancelled` | `get('IsCancelled')` | Whether the invoice is cancelled (bool) |

See [income classifications](../appendix/income-classifications) and
[expense classifications](../appendix/expenses-classifications) for the codes.

## Pagination

`pages()` takes the same parameters as `handle()`, follows the continuation token and yields each page.
myDATA pages the results only when `groupedPerDay` is `false`.

```php
foreach ((new RequestE3Info())->pages(dateFrom: '01/01/2026', dateTo: '31/12/2026') as $page) {
    foreach ($page->getE3Info() ?? [] as $info) {
        // ...
    }
}
```

To resume from a saved token, pass its keys as `nextPartitionKey` / `nextRowKey` to `pages()` or `handle()`.
