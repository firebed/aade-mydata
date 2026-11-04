# CancelReceivingNote <Badge type="tip" text="Provider only" />

```shell
# production
https://mydatapi.aade.gr/myDataProvider/CancelReceivingNote?mark={mark}&entityVatNumber={entityVatNumber}

# development
https://mydataapidev.aade.gr/myDataProvider/CancelReceivingNote?mark={mark}&entityVatNumber={entityVatNumber}
```

Cancels a receiving note (invoice types 10.1 and 10.2). There is no request body.

> [!WARNING]
> Provider channel only. On an ERP connection the call throws `UnsupportedChannelException` and sends nothing.

```php
use Firebed\AadeMyData\Http\CancelReceivingNote;
use Firebed\AadeMyData\Http\MyDataRequest;

MyDataRequest::init($userId, $subscriptionKey, 'dev', is_provider: true);

$responses = (new CancelReceivingNote)->handle(mark: '400001234567890', entityVatNumber: '888888888');
```

With the `MyData` client:

```php
use Firebed\AadeMyData\MyData;

$mydata = MyData::connect($userId, $subscriptionKey, 'dev', provider: true);

$responses = $mydata->cancelReceivingNote('400001234567890', '888888888');
```

## Parameters

| Parameter         | Required | Description                                      |
|-------------------|----------|--------------------------------------------------|
| `mark`            | Yes      | MARK of the receiving note to cancel             |
| `entityVatNumber` | Yes      | VAT number of the entity that issued the note    |

## Result

`handle()` returns a `ResponseDoc`, a list of [Response](../models/response-type) objects.
On success the cancellation gets its own MARK and the receiving note is cancelled.
On failure the response holds the errors.

```php
$response = $responses->first();

if ($response->isSuccessful()) {
    echo $response->getCancellationMark();
} else {
    foreach ($response->getErrors() ?? [] as $error) {
        echo $error->getCode().': '.$error->getMessage().PHP_EOL;
    }
}
```

> [!TIP]
> To cancel a delivery note use [CancelDeliveryNote](./cancel-delivery-note).
