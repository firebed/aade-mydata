# ContinuationToken

When a search returns too many results, myDATA sends them in pages. Each page except the last has a
`ContinuationToken`. Pass its keys to the next call to get the next page.

```php
use Firebed\AadeMyData\Http\RequestDocs;

$page = (new RequestDocs())->handle(mark: '0');
$token = $page->getContinuationToken();

if ($token !== null) {
    $next = (new RequestDocs())->handle(
        mark: '0',
        nextPartitionKey: $token->getNextPartitionKey(),
        nextRowKey: $token->getNextRowKey(),
    );
}
```

Or let `pages()` follow the tokens for you:

```php
foreach ((new RequestDocs())->pages(mark: '0') as $page) {
    // $page is a RequestedDoc
}
```

| Field            | Getter                  | Type     | Description               |
|------------------|-------------------------|----------|---------------------------|
| nextPartitionKey | `getNextPartitionKey()` | `string` | Key for the next call     |
| nextRowKey       | `getNextRowKey()`       | `string` | Key for the next call     |

Call the same method with the same parameters, plus the two keys.

Models with a continuation token: [RequestedDoc](requested-doc-type), `RequestedBookInfo` ([BookInfo](book-info-type)),
`RequestedVatInfo` ([VatInfo](vat-info-type)), [RequestedE3Info](e3-info-type) and
[RequestedProviderDoc](requested-provider-doc-type).
