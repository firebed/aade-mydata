# RequestedProviderDoc

`RequestProviderDocs` (provider channel) returns a `RequestedProviderDoc`. It lists the invoices the provider sent
for one issuer, as `ProviderInvoice` entries. It does not contain the invoices themselves.

```php
use Firebed\AadeMyData\MyData;

$mydata = MyData::connect($userId, $subscriptionKey, 'dev', provider: true);

$doc = $mydata->requestProviderDocs(issuerVat: '123456789', mark: $lastKnownMark);

foreach ($doc->getInvoices() as $invoice) {
    $invoice->getIssuerVAT();
    $invoice->getInvoiceProviderMark(); // int
    $invoice->getInvoiceUid();
    $invoice->getAuthenticationCode();
}

// all entries with this uid. More than one means myDATA registered the invoice twice
$doc->findByUid($uid);
```

`RequestedProviderDoc`:

| Method                   | Returns                                         | Description |
|--------------------------|-------------------------------------------------|-------------|
| `getContinuationToken()` | [ContinuationToken](continuation-token-type)    | Key to the next page, `null` on the last page |
| `getInvoices()`          | `ProviderInvoice[]`                             | The entries, empty array when there are none |
| `findByUid($uid)`        | `ProviderInvoice[]`                             | The entries with this uid (case-insensitive) |

`ProviderInvoice`:

| Field               | Getter                     | Type     | Description |
|---------------------|----------------------------|----------|-------------|
| issuerVAT           | `getIssuerVAT()`           | `string` | VAT number of the issuer |
| invoiceProviderMark | `getInvoiceProviderMark()` | `int`    | MARK of the invoice |
| invoiceUid          | `getInvoiceUid()`          | `string` | Uid of the invoice |
| authenticationCode  | `getAuthenticationCode()`  | `string` | Authentication code |

> [!NOTE]
> myDATA stores submissions from a queue. An invoice shows up here only after myDATA stores it, which in production
> can take hours after the `SendInvoices` answer. A missing invoice does not mean it was not registered.
