# ReceiverInfoDoc

`RequestReceiverInfo` (provider channel) returns a `ReceiverInfoDoc` for an entity: the providers it invoices
through and the emails where it accepts e-invoices.

```php
use Firebed\AadeMyData\MyData;

$mydata = MyData::connect($userId, $subscriptionKey, 'dev', provider: true);

$info = $mydata->requestReceiverInfo('123456789');

$info->getReceptionProviders()?->vatNumbers(); // ['800000001', ...]
$info->getReceptionEmails()?->all();           // ['invoices@example.com', ...]
```

| Field              | Getter                    | Type                 | Description |
|--------------------|---------------------------|----------------------|-------------|
| receptionProviders | `getReceptionProviders()` | `ReceptionProviders` | Providers of the entity. A list of `ProviderInfo` |
| receptionEmails    | `getReceptionEmails()`    | `ReceptionEmails`    | Emails of the entity. A list of `string` |

`ReceptionProviders::vatNumbers()` returns the VAT numbers of the providers. Each `ProviderInfo` has
`getProviderVATNumber()`.

A successful provider `SendInvoices` [Response](response-type) has the same two lists:
`$response->getReceptionProviders()` and `$response->getReceptionEmails()`.
