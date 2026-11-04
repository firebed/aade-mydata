# RequestProviderDocs <Badge type="tip" text="Provider only" />

```shell
# production
https://mydatapi.aade.gr/myDataProvider/RequestTransmittedDocs?issuervat={issuervat}&mark={mark}

# development
https://mydataapidev.aade.gr/myDataProvider/RequestTransmittedDocs?issuervat={issuervat}&mark={mark}
```

Lists the invoices the provider sent for one issuer, with a MARK greater than `mark`.
For each invoice you get the issuer VAT number, the MARK, the UID and the authentication code. You do not get
the invoice itself: a provider cannot read the issuer's invoices without the issuer's own myDATA credentials.

> [!WARNING]
> Provider channel only. This is the provider version of `RequestTransmittedDocs`. The ERP
> [RequestTransmittedDocs](./request-transmitted-docs) class throws `UnsupportedChannelException` on a provider connection.

```php
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\RequestProviderDocs;

MyDataRequest::init($userId, $subscriptionKey, 'dev', is_provider: true);

$doc = (new RequestProviderDocs)->handle(issuerVat: '123456789', mark: $lastKnownMark);

foreach ($doc->getInvoices() as $invoice) {
    $invoice->getIssuerVAT();
    $invoice->getInvoiceProviderMark(); // int
    $invoice->getInvoiceUid();
    $invoice->getAuthenticationCode();
}
```

With the `MyData` client:

```php
use Firebed\AadeMyData\MyData;

$mydata = MyData::connect($userId, $subscriptionKey, 'dev', provider: true);

$doc = $mydata->requestProviderDocs(issuerVat: '123456789', mark: $lastKnownMark);
```

## Parameters

| Parameter          | Required | Description                                                         |
|--------------------|----------|---------------------------------------------------------------------|
| `issuerVat`        | Yes      | VAT number of the issuer                                            |
| `mark`             | Yes      | Return invoices with a MARK greater than this. Default `0`          |
| `nextPartitionKey` | No       | Pagination (continuation token)                                     |
| `nextRowKey`       | No       | Pagination (continuation token)                                     |

## Result

`handle()` returns a `RequestedProviderDoc`.

| Method                   | Returns                   | Description                                         |
|--------------------------|---------------------------|-----------------------------------------------------|
| `getInvoices()`          | `list<ProviderInvoice>`   | The invoices of this page                           |
| `findByUid($uid)`        | `list<ProviderInvoice>`   | The invoices with this UID                          |
| `getContinuationToken()` | `?ContinuationToken`      | Set when there are more pages                       |

`findByUid()` returning more than one invoice means myDATA registered the same invoice twice.

## All pages

`invoices()` follows the continuation token and yields every `ProviderInvoice` of every page.
`pages()` yields each `RequestedProviderDoc` page.

```php
use Firebed\AadeMyData\Http\RequestProviderDocs;

foreach ((new RequestProviderDocs)->invoices('123456789', $lastKnownMark) as $invoice) {
    // ...
}

// With the MyData client
foreach ($mydata->request(new RequestProviderDocs)->invoices('123456789', $lastKnownMark) as $invoice) {
    // ...
}
```

## Things to know

- myDATA stores submissions from a queue. An invoice shows up here only after it is stored. In production this can
  take hours after `SendInvoices` answered. A missing invoice may still be on its way.
- Invoices sent with [SendUnsignedInvoices](./send-unsigned-invoices) are not listed.
- On the sandbox, `mark: 0` listed every invoice, while a mark with fewer digits than the issuer's marks listed none.
  Pass `0` or a MARK that myDATA returned.
