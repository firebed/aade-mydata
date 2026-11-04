# SendPaymentsMethod

```shell
# production
https://mydatapi.aade.gr/myDATA/SendPaymentsMethod

# development
https://mydataapidev.aade.gr/SendPaymentsMethod

# production, provider channel
https://mydatapi.aade.gr/myDataProvider/SendPaymentsMethod

# development, provider channel
https://mydataapidev.aade.gr/myDataProvider/SendPaymentsMethod
```

Sends one or more payment methods for invoices you already sent. Use it when the payment comes after the invoice.

```php
use Firebed\AadeMyData\Enums\PaymentType;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\SendPaymentsMethod;
use Firebed\AadeMyData\Models\ECRToken;
use Firebed\AadeMyData\Models\PaymentMethod;
use Firebed\AadeMyData\Models\PaymentMethodDetail;

try {
    $detail = new PaymentMethodDetail();
    $detail->setType(PaymentType::POS);
    $detail->setAmount(1000);
    $detail->setTid('12345678');
    $detail->setECRToken(new ECRToken('ABC12345678', '123456')); // ECR id, 6-digit session number

    $paymentMethod = new PaymentMethod();
    $paymentMethod->setInvoiceMark(1234567890);
    $paymentMethod->addPaymentMethodDetails($detail);

    $responses = (new SendPaymentsMethod())->handle($paymentMethod);
} catch (MyDataException $e) {
    echo $e->getMessage();
}
```

With the `MyData` client:

```php
use Firebed\AadeMyData\MyData;

$mydata = MyData::connect($userId, $subscriptionKey, 'dev');

$responses = $mydata->sendPaymentsMethod($paymentMethod);
```

> [!NOTE]
> - At least one `PaymentMethodDetail` per invoice must be of type POS.
> - The `amount` values of a `PaymentMethod` must add up to the `totalGrossValue` of the invoice with that `invoiceMark`.

## Parameters

`handle()` takes one argument, in one of these forms:

| Argument | Description |
|---|---|
| `PaymentMethod` | Payment methods of one invoice |
| `PaymentMethod[]` | A list of them |
| `PaymentMethodsDoc` | A collection of them |

### PaymentMethod

| Field | Required | Description |
|---|---|---|
| `invoiceMark` | Yes | MARK of the invoice |
| `entityVatNumber` | No | VAT number of the entity. Send it only when a third party (e.g. a provider) calls |
| `paymentMethodDetails` | Yes | One or more `PaymentMethodDetail`, see [PaymentMethodDetail](../models/payment-method-detail-type) |

`paymentMethodMark` is set by myDATA.

## ERP or provider

For a POS payment (type 7), the signature depends on the channel:

| Channel | Method |
|---|---|
| ERP | `setECRToken(new ECRToken($signingAuthor, $sessionNumber))` |
| Provider | `setProvidersSignature(new ProvidersSignature($signingAuthor, $signature))` |

## Results

`handle()` returns a `ResponseDoc` with one [Response](../models/response-type) per `PaymentMethod`.
On success, `getPaymentMethodMark()` holds the MARK of the payment method.

```php
foreach ($responses as $response) {
    if ($response->isSuccessful()) {
        echo $response->getPaymentMethodMark() . PHP_EOL;
    } else {
        foreach ($response->getErrors() as $error) {
            echo $error->getCode() . ': ' . $error->getMessage() . PHP_EOL;
        }
    }
}
```
