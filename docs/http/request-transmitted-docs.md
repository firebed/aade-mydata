# RequestTransmittedDocs

```shell
# production
https://mydatapi.aade.gr/myDATA/RequestTransmittedDocs

# development
https://mydataapidev.aade.gr/RequestTransmittedDocs
```

Returns invoices, classifications and cancellations that you sent yourself.
You get the records with a MARK greater than `mark`. For invoices other users sent to you, use
[RequestDocs](request-docs).

> [!WARNING]
> ERP channel only. On a provider connection the request throws `UnsupportedChannelException`.
> Providers use [RequestProviderDocs](request-provider-docs).

```php
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\RequestTransmittedDocs;

try {
    $doc = (new RequestTransmittedDocs())->handle(mark: '1234567890');

    foreach ($doc->getInvoices() ?? [] as $invoice) {
        // ...
    }
} catch (MyDataException $e) {
    echo "Connection error: " . $e->getMessage();
}
```

With the `MyData` client:

```php
use Firebed\AadeMyData\MyData;

$mydata = MyData::connect($userId, $subscriptionKey, 'dev');

$doc = $mydata->requestTransmittedDocs(mark: '1234567890');
```

## Parameters

| Parameter | Required | Description |
|---|---|---|
| `mark` | Yes | Return records with a MARK greater than this |
| `entityVatNumber` | No | VAT number of the entity you search for. Send it only when a third party (e.g. an accountant) calls |
| `dateFrom` | No | Start of the issue date range, `dd/MM/yyyy` |
| `dateTo` | No | End of the issue date range, `dd/MM/yyyy` |
| `receiverVatNumber` | No | VAT number of the counterpart |
| `invType` | No | Invoice type, an `InvoiceType` case or its code (see [invoice types](../appendix/invoice-types)) |
| `maxMark` | No | Return records with a MARK up to and including this |
| `nextPartitionKey` | No | Pagination key from the previous page |
| `nextRowKey` | No | Pagination key from the previous page |

- A parameter you leave out matches every value.
- If you give only one of `dateFrom` / `dateTo`, the search covers that single date.
- `receiverVatNumber` and `invType` must match exactly.

## Results

`handle()` returns a [RequestedDoc](../models/requested-doc-type).

```php
use Firebed\AadeMyData\Enums\InvoiceType;

$doc = (new RequestTransmittedDocs())->handle(
    mark: '1234567890',
    dateFrom: '01/01/2026',
    dateTo: '31/12/2026',
    receiverVatNumber: '123456789',
    invType: InvoiceType::TYPE_1_1,
);

$doc->getInvoices();                // InvoicesDoc|null
$doc->getCancelledInvoices();       // CancelledInvoicesDoc|null
$doc->getIncomeClassifications();   // IncomeClassificationsDoc|null
$doc->getExpensesClassifications(); // ExpensesClassificationsDoc|null
$doc->getPaymentMethods();          // PaymentMethodsDoc|null
$doc->getContinuationToken();       // ContinuationToken|null
```

## Pagination

When the results are too large, myDATA returns them in pages. Each page has a continuation token.

`pages()` follows the token and yields each page. `invoices()` yields each invoice of every page.
Both take the same parameters as `handle()`. Pages are fetched only as you iterate.

```php
use Firebed\AadeMyData\Http\RequestTransmittedDocs;

foreach ((new RequestTransmittedDocs())->pages(mark: '1234567890', dateFrom: '01/01/2026', dateTo: '31/01/2026') as $page) {
    foreach ($page->getInvoices() ?? [] as $invoice) {
        // ...
    }
}

foreach ((new RequestTransmittedDocs())->invoices(mark: '1234567890') as $invoice) {
    // ...
}
```

> [!TIP]
> myDATA limits the number of document requests per subscription key. For long runs use
> `withThrottle()` / `withRateLimitWait()` on the connection, see [installation](../installation).

To page by hand, pass the token keys to the next call:

```php
$request = new RequestTransmittedDocs();

$page1 = $request->handle(mark: '1234567890');
$token = $page1->getContinuationToken();

if ($token !== null) {
    $page2 = $request->handle(
        mark: '1234567890',
        nextPartitionKey: $token->getNextPartitionKey(),
        nextRowKey: $token->getNextRowKey(),
    );
}
```

`pages()` and `invoices()` also take `nextPartitionKey` / `nextRowKey`, so you can resume from a saved token.
