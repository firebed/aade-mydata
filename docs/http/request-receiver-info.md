# RequestReceiverInfo <Badge type="tip" text="Provider only" />

```shell
# production
https://mydatapi.aade.gr/myDataProvider/RequestReceiverInfo?vatNumber={vatNumber}

# development
https://mydataapidev.aade.gr/myDataProvider/RequestReceiverInfo?vatNumber={vatNumber}
```

For one entity, returns:

- the VAT numbers of the providers it invoices through
- the emails where it accepts invoices from e-invoicing

> [!WARNING]
> Provider channel only. On an ERP connection the call throws `UnsupportedChannelException` and sends nothing.

```php
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\RequestReceiverInfo;

MyDataRequest::init($userId, $subscriptionKey, 'dev', is_provider: true);

$info = (new RequestReceiverInfo)->handle('123456789');

$info->getReceptionProviders()?->vatNumbers(); // ['800000001', '800000002']
$info->getReceptionEmails()?->all();           // ['invoices@example.com']
```

With the `MyData` client:

```php
use Firebed\AadeMyData\MyData;

$mydata = MyData::connect($userId, $subscriptionKey, 'dev', provider: true);

$info = $mydata->requestReceiverInfo('123456789');
```

## Parameters

| Parameter   | Required | Description                 |
|-------------|----------|-----------------------------|
| `vatNumber` | Yes      | VAT number of the entity    |

## Result

`handle()` returns a `ReceiverInfoDoc`.

| Method                    | Returns               | Description                                |
|---------------------------|-----------------------|--------------------------------------------|
| `getReceptionProviders()` | `?ReceptionProviders` | Providers the entity invoices through      |
| `getReceptionEmails()`    | `?ReceptionEmails`    | Emails where the entity accepts invoices   |

Both return `null` when myDATA sends no list, so use `?->`.

`ReceptionProviders` is a list of `ProviderInfo`. `ReceptionEmails` is a list of strings.
Both are collections: you can `foreach` them, `count()` them, and call `all()`, `first()` and `isEmpty()`.

| Class                | Method                   | Returns         | Description                         |
|----------------------|--------------------------|-----------------|-------------------------------------|
| `ReceptionProviders` | `vatNumbers()`           | `list<string>`  | VAT numbers of all providers        |
| `ProviderInfo`       | `getProviderVATNumber()` | `?string`       | VAT number of one provider          |
| `ReceptionEmails`    | `all()`                  | `list<string>`  | All emails                          |

```php
foreach ($info->getReceptionProviders() ?? [] as $provider) {
    echo $provider->getProviderVATNumber();
}

if ($info->getReceptionEmails()?->isNotEmpty()) {
    $email = $info->getReceptionEmails()->first();
}
```

> [!TIP]
> A successful provider [SendInvoices](./send-invoices) response carries the same lists for the recipient:
> `$response->getReceptionProviders()` and `$response->getReceptionEmails()`.
