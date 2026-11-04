# Testing

Your app's tests should not call myDATA. `MyData::fake()` answers every request with a fake
response, whatever connection it uses (default, `Connection`, `usingGateway()`), and records it
for assertions. No credentials needed.

```php
use Firebed\AadeMyData\Http\SendInvoices;
use Firebed\AadeMyData\MyData;

protected function tearDown(): void
{
    MyData::stopFaking();
}

public function test_an_order_issues_an_invoice(): void
{
    $fake = MyData::fake();

    $this->orders->issueInvoice($order); // your code, which sends an invoice

    $fake->assertSent(SendInvoices::class, fn (SendInvoices $request) => $request->getInvoicesDoc()->count() === 1);
    $fake->assertSentCount(1);
}
```

The assertions need PHPUnit (or Pest). To build test invoices fast, see [Factories](./factories).

## Default responses

If you give no response for a request:

| Request | Response |
|---|---|
| `SendInvoices` | Success per invoice, with mark (from 400000000000001), uid and authenticationCode |
| `SendUnsignedInvoices` | Success per invoice, with mark and uid, no authenticationCode |
| `SendIncomeClassification`, `SendExpensesClassification` | Success per classification, with classificationMark |
| `SendPaymentsMethod` | Success per payment, with paymentMethodMark |
| `CancelInvoice`, `CancelDeliveryNote`, `CancelReceivingNote` | Success with cancellationMark |
| `RequestDocs`, `RequestTransmittedDocs`, `RequestMyIncome`, `RequestMyExpenses`, `RequestVatInfo`, `RequestE3Info` | No results |
| `RequestProviderDocs`, `RequestReceiverInfo` | No results |
| Digital Goods Movement, statements | `LogicException` until you give a response |

The uid is set only when the invoice has the fields it is computed from.

## Responses per request

```php
use Firebed\AadeMyData\Exceptions\RateLimitExceededException;
use Firebed\AadeMyData\Http\CancelInvoice;
use Firebed\AadeMyData\Http\RequestDocs;
use Firebed\AadeMyData\Http\RequestVatInfo;
use Firebed\AadeMyData\Http\SendInvoices;
use Firebed\AadeMyData\Testing\FakeGateway;
use Firebed\AadeMyData\Testing\RecordedRequest;

MyData::fake([
    // A rejection, as myDATA returns it (statusCode ValidationError)
    SendInvoices::class => FakeGateway::rejected(101, 'Invalid VAT number'),

    // One response per call, in order (e.g. pages with a continuationToken)
    RequestDocs::class => FakeGateway::sequence($page1, $page2),

    // An exception: test how your app handles the rate limit
    RequestVatInfo::class => new RateLimitExceededException(retryAfter: 300),

    // A closure: the response depends on the request
    CancelInvoice::class => fn (CancelInvoice $request, RecordedRequest $sent) => FakeGateway::accepted(),

    // Any other request
    '*' => file_get_contents(__DIR__.'/stubs/response.xml'),
]);
```

You can also add a response later: `$fake->respond(SendInvoices::class, $response)`.

A class answers and asserts only its own requests. `SendInvoices::class` does not cover
`SendUnsignedInvoices`, and `CancelInvoice::class` does not cover the note cancellations, because
they are different myDATA actions. An abstract class covers its subclasses:

| Abstract class | Covers |
|---|---|
| `MyDataGetRequest` | `RequestDocs`, `RequestTransmittedDocs` |
| `InvoicesDocRequest` | `SendInvoices`, `SendUnsignedInvoices` |
| `CancellationRequest` | `CancelInvoice`, `CancelDeliveryNote`, `CancelReceivingNote` |
| `RequestBookInfo` | `RequestMyIncome`, `RequestMyExpenses` |

A response can be:

- an XML string, as myDATA would send it,
- a model: `ResponseDoc`, `Response` (wrapped in a `ResponseDoc`), `RequestedDoc`, `RequestedVatInfo`, ...,
- a list of `Response` (one per document) or of `Invoice` (returned as a `RequestedDoc`),
- an exception, which is thrown,
- a closure that gets the request and the `RecordedRequest` and returns one of the above,
- `FakeGateway::sequence(...)`, with `->whenEmpty($response)` for the calls after it runs out.

Helpers:

| Method | Returns |
|---|---|
| `FakeGateway::accepted($mark = null, $uid = null)` | A successful `Response` |
| `FakeGateway::rejected($code, $message, $statusCode = 'ValidationError')` | A `Response` with one error |
| `FakeGateway::sequence(...$responses)` | One response per call, in order |

In a `ResponseDoc`, a response without `index` gets its position, and a successful response without
a mark gets the next mark. For two invoices where the second is rejected:

```php
MyData::fake([SendInvoices::class => [FakeGateway::accepted(), FakeGateway::rejected(102, 'Wrong total')]]);
```

## Assertions

```php
$fake->assertSent(SendInvoices::class);
$fake->assertSent(RequestDocs::class, fn (RequestDocs $request, RecordedRequest $sent) => $sent->query['mark'] === '0');
$fake->assertNotSent(CancelInvoice::class);
$fake->assertSentCount(2);                      // all requests
$fake->assertSentCount(1, SendInvoices::class); // only SendInvoices
$fake->assertNothingSent();

$fake->recorded(SendInvoices::class)[0]->body;  // the XML that was sent
```

`RecordedRequest` has:

| Member | Value |
|---|---|
| `request` | The request object |
| `method` | `'GET'` or `'POST'` |
| `query` | The query parameters |
| `body` | The request XML, null when the request has no body |
| `document()` | The request XML as a `DOMDocument` |
| `count()` | How many documents were sent (invoices, classifications, ...) |
| `action()` | The myDATA action, e.g. `'SendInvoices'` |

Connection listeners (`afterSending()`, `withLogger()`) and validation (`withValidation()`) still
run with the fake, so you can test them too.

## Running the package's tests

```shell
composer test
```
