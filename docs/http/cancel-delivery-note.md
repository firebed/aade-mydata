# CancelDeliveryNote <Badge type="tip" text="Provider only" />

```shell
# production
https://mydatapi.aade.gr/myDataProvider/CancelDeliveryNote?mark={mark}&entityVatNumber={entityVatNumber}

# development
https://mydataapidev.aade.gr/myDataProvider/CancelDeliveryNote?mark={mark}&entityVatNumber={entityVatNumber}
```

Cancels a delivery note without sending a new one. There is no request body.

> [!WARNING]
> Provider channel only. On an ERP connection the call throws `UnsupportedChannelException` and sends nothing.

```php
use Firebed\AadeMyData\Http\CancelDeliveryNote;
use Firebed\AadeMyData\Http\MyDataRequest;

MyDataRequest::init($userId, $subscriptionKey, 'dev', is_provider: true);

$responses = (new CancelDeliveryNote)->handle(mark: '400001234567890', entityVatNumber: '888888888');
```

With the `MyData` client:

```php
use Firebed\AadeMyData\MyData;

$mydata = MyData::connect($userId, $subscriptionKey, 'dev', provider: true);

$responses = $mydata->cancelDeliveryNote('400001234567890', '888888888');
```

## Parameters

| Parameter         | Required | Description                                      |
|-------------------|----------|--------------------------------------------------|
| `mark`            | Yes      | MARK of the delivery note to cancel              |
| `entityVatNumber` | Yes      | VAT number of the entity that issued the note    |

## Result

`handle()` returns a `ResponseDoc`, a list of [Response](../models/response-type) objects.
On success the cancellation gets its own MARK and the delivery note is cancelled.
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
> To cancel a receiving note (types 10.1, 10.2) use [CancelReceivingNote](./cancel-receiving-note).
