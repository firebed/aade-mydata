# CancelInvoice

```shell
# production
https://mydatapi.aade.gr/myDATA/CancelInvoice

# development
https://mydataapidev.aade.gr/CancelInvoice
```

Cancels an invoice by its MARK, without sending a new one. One invoice per call.

```php
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\CancelInvoice;

try {
    $responses = (new CancelInvoice())->handle('1234567890');
} catch (MyDataException $e) {
    echo "Connection error: " . $e->getMessage();
}
```

With the `MyData` client:

```php
use Firebed\AadeMyData\MyData;

$mydata = MyData::connect($userId, $subscriptionKey, 'dev');

$responses = $mydata->cancelInvoice('1234567890');
```

## Parameters

| Parameter | Required | Description |
|---|---|---|
| `mark` | Yes | MARK of the invoice to cancel |
| `entityVatNumber` | No | VAT number of the issuer. Send it only when a third party (e.g. an accountant) cancels the invoice |

```php
$responses = (new CancelInvoice())->handle(mark: '1234567890', entityVatNumber: '888888888');
```

## Results

`handle()` returns a `ResponseDoc` with one [Response](../models/response-type).
On success the cancellation gets its own MARK and the invoice counts as cancelled.
On failure the response holds the errors.

```php
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\CancelInvoice;

try {
    $response = (new CancelInvoice())->handle('1234567890')->first();

    if ($response->isSuccessful()) {
        echo "Cancellation MARK: " . $response->getCancellationMark();
    } else {
        foreach ($response->getErrors() as $error) {
            echo $error->getCode() . ': ' . $error->getMessage() . PHP_EOL;
        }
    }
} catch (MyDataException $e) {
    echo "Connection error: " . $e->getMessage();
}
```

The result is a list so that it matches the other send methods. It always has one item. Read it with
`$responses->first()`, `$responses[0]` or a `foreach`.

## After the cancellation

In [RequestTransmittedDocs](request-transmitted-docs) results:

- `RequestedDoc::getCancelledInvoices()` lists the cancelled invoice.
- The invoice in `RequestedDoc::getInvoices()` has `cancelledByMark` set to the cancellation MARK
  (the same value as `$response->getCancellationMark()`).

```xml
<RequestedDoc>
    <invoicesDoc>
        <invoice>
            <uid>5AD65A46SFD5498SDV416WS5F1VS65VDFS65VDF</uid>
            <mark>800000165789544</mark>
            <cancelledByMark>800000165989544</cancelledByMark>
        </invoice>
    </invoicesDoc>
    <cancelledInvoicesDoc>
        <cancelledInvoice>
            <invoiceMark>800000165789544</invoiceMark>
            <cancellationMark>800000165989544</cancellationMark>
            <cancellationDate>2021-12-12</cancellationDate>
        </cancelledInvoice>
    </cancelledInvoicesDoc>
</RequestedDoc>
```
