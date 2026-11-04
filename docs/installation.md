# Installation

```shell
composer require firebed/aade-mydata
```

### Requirements

```json
{
    "require": {
      "php": "^8.3",
      "ext-dom": "*",
      "ext-libxml": "*",
      "guzzlehttp/guzzle": "^7.8 || ^8.0",
      "psr/log": "^2.0 || ^3.0"
    }
}
```

## Credentials

You need a user id and a subscription key. You get them when you sign up to the myDATA REST API.

## Links

- [Sandbox (AADE)](https://www.aade.gr/mydata/dokimastiko-periballon)
- [Sign up to the sandbox](https://mydata-dev-register.azurewebsites.net/)
- [Sign up to production](https://www.aade.gr/mydata)
- [Official AADE myDATA documentation (PDF v2.0.2)](https://www.aade.gr/sites/default/files/2026-09/myDATA%20API%20Documentation%20v2.0.2_official_erp_1.pdf)

## Setup

Set the environment and the credentials once:

```php
use Firebed\AadeMyData\Http\MyDataRequest;

$env = "dev"; // "prod" for production
$user_id = "your-user-id";
$subscription_key = "your-subscription-key";

MyDataRequest::setEnvironment($env);
MyDataRequest::setCredentials($user_id, $subscription_key);
```

Or in one call:

```php
MyDataRequest::init($user_id, $subscription_key, $env);
```

For a provider (e-invoicing provider channel), pass `true` as the fourth argument:
`MyDataRequest::init($user_id, $subscription_key, $env, true)`.

During development, you may need to turn off client verification if you don't use https:

```php
MyDataRequest::verifyClient(false);
```

### Connection per tenant (`Connection` and `MyData`)

The static methods above set the default connection of the process. If one app serves many
tenants, or you prefer an object, create a `Connection` and a `MyData` client on it.
See [MyData client](mydata-client) for every option, tenants and both channels.

```php
use Firebed\AadeMyData\Http\Connection;
use Firebed\AadeMyData\Http\SendInvoices;
use Firebed\AadeMyData\MyData;

$mydata = MyData::connect($user_id, $subscription_key, $env);

// or fully configured
$connection = Connection::make($user_id, $subscription_key, $env)
    ->withTimeout(30)
    ->withRetries(3);

$mydata = new MyData($connection);

$responses = $mydata->sendInvoices($invoices);

// Any request can use a connection
(new SendInvoices())->using($connection)->handle($invoices);
```

`Connection::make()` and `MyData::connect()` use the sandbox unless you pass `'prod'`.
Call `->asProvider()` for the provider channel.

### Retries

```php
MyDataRequest::setRetries(3, 500);           // default connection
Connection::make(...)->withRetries(3, 500);  // one connection
```

Requests that fail with 429, 502, 503, 504 or a connection failure are retried. The delay (ms)
doubles on every attempt. Timeouts are never retried, because myDATA may have processed the request.

### Rate limits

myDATA limits each subscription key by the number of calls in a window of about five minutes.
Measured on the sandbox (November 2026): `RequestDocs` and `RequestTransmittedDocs` share one counter
of 11 calls per 300-second window. After that, every call to them answers HTTP 429 with a `Retry-After`
header and the message `Rate limit is exceeded. Try again in 298 seconds.` until the window ends.
`RequestVatInfo`, `RequestMyIncome` and `RequestE3Info` kept working, so the counters are per group of
operations. Production limits are not published and may differ. The library reads `Retry-After`.

- Short waits (up to 10 seconds) are retried with `withRetries()`. Longer waits are not retried.
  The call fails with `RateLimitExceededException`. Its `getRetryAfter()` / `getRetryAt()` tell you
  when the key is accepted again.
- `withRateLimitWait(600)` waits for `Retry-After` (up to this many seconds) and sends the call again.
- `withThrottle(28000)` keeps at least this many ms between two calls of the connection, so a long
  download stays under the limit.

```php
use Firebed\AadeMyData\Http\RequestTransmittedDocs;

$connection = Connection::make($user_id, $subscription_key, $env)
    ->withThrottle(28_000)
    ->withRateLimitWait(600);

foreach ((new RequestTransmittedDocs())->using($connection)->invoices(mark: (string) $lastMark) as $invoice) {
    $lastMark = max($lastMark, (int) $invoice->getMark());
}
```

Keep the last mark you processed and only ask for newer invoices. If you stop on the exception,
continue later from the `continuationToken` of the last page (`pages(..., nextPartitionKey:, nextRowKey:)`).
Static: `MyDataRequest::setThrottle()` and `MyDataRequest::setRateLimitWait()`.

### Validation before sending

With `withValidation()`, the XML of every call that sends a document (`SendInvoices`, the classifications,
`SendPaymentsMethod`, the delivery note calls, `SendStatement`) is checked against the myDATA XSD before it
is sent. An invalid document is not sent. You get a `SchemaValidationException` with the failing fields,
instead of spending a call that myDATA would reject.

```php
use Firebed\AadeMyData\Exceptions\SchemaValidationException;

$mydata = new MyData(Connection::make($user_id, $subscription_key, $env)->withValidation());

try {
    $mydata->sendInvoices($invoices);
} catch (SchemaValidationException $e) {
    $e->getErrors(); // [['field' => '{http://www.aade.gr/myDATA/invoice/v1.0}totalGrossValue', 'message' => '...']]
}
```

It is off by default. Static: `MyDataRequest::setValidation()`.

To check one invoice without a connection, call `$invoice->validate()`. It returns the errors, or an empty array.

### Logging and listeners

`withLogger()` writes every call to a PSR-3 logger (Monolog, Laravel's `Log`, ...): success as `info`,
a retry or a 429 as `warning`, any other failure as `error`.

- Credentials are never logged.
- Without `withBodies: true`, the log also leaves out the XML and the query parameters that identify
  someone (VAT numbers, QR URLs). Failure messages are logged without query and VAT numbers, and the
  exception is named (class, code) instead of attached.
- A new `withLogger()` replaces the old one. `withLogger(null)` removes it.

```php
use Firebed\AadeMyData\Http\Exchange;
use Firebed\AadeMyData\Http\Retry;

$connection = Connection::make($user_id, $subscription_key, $env)
    ->withLogger($logger)
    ->afterSending(fn (Exchange $e) => AuditLog::create([
        'action' => $e->action(),
        'request' => $e->requestBody,
        'response' => $e->responseBody,     // null when the call failed
        'error' => $e->exception?->getMessage(),
    ]))
    ->beforeRetry(fn (Retry $r) => logger()->notice("Retry {$r->attempt} in {$r->delay} ms"));
```

`afterSending()` runs once per call, after its retries. `beforeRetry()` runs before each retry.
A listener that throws is skipped. It never hides the myDATA response (and the MARKs) or the failure of the call.

A listener with a key (`afterSending($audit, key: 'audit')`) replaces any listener with the same key.
So code that runs many times (worker boot, queue job) registers it once. Remove one with
`withoutListener('audit')` (or with the listener itself), or all with `withoutListeners()`.
The logger stays. `withLogger(null)` removes it.

Static: `MyDataRequest::setLogger()`, `afterSending()`, `beforeRetry()`, `removeListener()`, `removeListeners()`.

## Available methods

Each request is a class in `Firebed\AadeMyData\Http`, and a method on the `MyData` client.
Requests marked "ERP only" or "Provider only" throw `UnsupportedChannelException` on the other channel.

### Invoices

| Request | `MyData` method | Channel |
|---|---|---|
| [SendInvoices](./http/send-invoices) | `sendInvoices()` | |
| [CancelInvoice](./http/cancel-invoice) | `cancelInvoice()` | |
| [RequestDocs](./http/request-docs) | `requestDocs()` | ERP only |
| [RequestTransmittedDocs](./http/request-transmitted-docs) | `requestTransmittedDocs()` | ERP only |
| [SendPaymentsMethod](./http/send-payments-method) | `sendPaymentsMethod()` | |
| [SendIncomeClassification](./http/send-income-classification) | `sendIncomeClassification()` | |
| [SendExpensesClassification](./http/send-expenses-classification) | `sendExpensesClassification()` | |

### Reports

| Request | `MyData` method | Channel |
|---|---|---|
| [RequestMyIncome](./http/request-my-income) | `requestMyIncome()` | |
| [RequestMyExpenses](./http/request-my-expenses) | `requestMyExpenses()` | |
| [RequestVatInfo](./http/request-vat-info) | `requestVatInfo()` | |
| [RequestE3Info](./http/request-e3-info) | `requestE3Info()` | |

`RequestMyIncome` and `RequestMyExpenses` share the base class [RequestBookInfo](./http/request-book-info).

### Providers

| Request | `MyData` method | Channel |
|---|---|---|
| [RequestProviderDocs](./http/request-provider-docs) | `requestProviderDocs()` | Provider only |
| [RequestReceiverInfo](./http/request-receiver-info) | `requestReceiverInfo()` | Provider only |
| [SendUnsignedInvoices](./http/send-unsigned-invoices) | `sendUnsignedInvoices()` | Provider only |
| [CancelDeliveryNote](./http/cancel-delivery-note) | `cancelDeliveryNote()` | Provider only |
| [CancelReceivingNote](./http/cancel-receiving-note) | `cancelReceivingNote()` | Provider only |
| [SendStatement](./http/statements) | `sendStatement()` | Provider only |
| [RequestStatements](./http/statements) | `requestStatements()` | Provider only |
| [RecallStatement](./http/statements) | `recallStatement()` | Provider only |

### Digital Goods Movement

Classes in `Firebed\AadeMyData\Http\DigitalGoodsMovement`. All are ERP only.

| Request | `MyData` method |
|---|---|
| [RegisterTransfer](./http/digital-goods-movement#registertransfer) | `registerTransfer()` |
| [ConfirmDeliveryOutcome](./http/digital-goods-movement#confirmdeliveryoutcome) | `confirmDeliveryOutcome()` |
| [ConfirmDeliveryReturn](./http/digital-goods-movement#confirmdeliveryreturn) | `confirmDeliveryReturn()` |
| [RejectDeliveryNote](./http/digital-goods-movement#rejectdeliverynote) | `rejectDeliveryNote()` |
| [RequestDeliveryNoteStatus](./http/digital-goods-movement#requestdeliverynotestatus) | `requestDeliveryNoteStatus()`, `requestDeliveryNoteStatusByQrUrl()` |
| [GenerateGroupQrCode](./http/digital-goods-movement#generategroupqrcode) | `generateGroupQrCode()` |
| [RequestGroupQrDetails](./http/digital-goods-movement#requestgroupqrdetails) | `requestGroupQrDetails()` |

### Other

- VAT number lookup: [SearchVat](./http/search-vat) (separate package, `firebed/vat-registry`)

## XSD files

- [ConfirmDeliveryOutcome-v2.0.2.xsd](../xsd/ConfirmDeliveryOutcome-v2.0.2.xsd)
- [ConfirmDeliveryReturn-v2.0.2.xsd](../xsd/ConfirmDeliveryReturn-v2.0.2.xsd)
- [expensesClassification-v2.0.2.xsd](../xsd/expensesClassification-v2.0.2.xsd)
- [GenerateGroupQRCode-v2.0.2.xsd](../xsd/GenerateGroupQRCode-v2.0.2.xsd)
- [GenerateGroupQRCodeResponse-v2.0.2.xsd](../xsd/GenerateGroupQRCodeResponse-v2.0.2.xsd)
- [GetDeliveryStatusResponse-v2.0.2.xsd](../xsd/GetDeliveryStatusResponse-v2.0.2.xsd)
- [incomeClassification-v2.0.2.xsd](../xsd/incomeClassification-v2.0.2.xsd)
- [InvoicesDoc-v2.0.2.xsd](../xsd/InvoicesDoc-v2.0.2.xsd)
- [InvoicesDoc-v2.0.2_aade_detailed.xsd](../xsd/InvoicesDoc-v2.0.2_aade_detailed.xsd)
- [paymentMethods-v2.0.2.xsd](../xsd/paymentMethods-v2.0.2.xsd)
- [RegisterTransfer-v2.0.2.xsd](../xsd/RegisterTransfer-v2.0.2.xsd)
- [RejectDeliveryNote-v2.0.2.xsd](../xsd/RejectDeliveryNote-v2.0.2.xsd)
- [RequestE3InfoResponse-v2.0.2.xsd](../xsd/RequestE3InfoResponse-v2.0.2.xsd)
- [requestedInvoicesDoc-v2.0.2.xsd](../xsd/requestedInvoicesDoc-v2.0.2.xsd)
- [RequestedProviderDoc-v2.0.2.xsd](../xsd/RequestedProviderDoc-v2.0.2.xsd)
- [RequestedStatementDoc-v2.0.2.xsd](../xsd/RequestedStatementDoc-v2.0.2.xsd)
- [RequestGroupQRDetailsResponse-v2.0.2.xsd](../xsd/RequestGroupQRDetailsResponse-v2.0.2.xsd)
- [RequestVatInfoResponse-v2.0.2.xsd](../xsd/RequestVatInfoResponse-v2.0.2.xsd)
- [response-v2.0.2.xsd](../xsd/response-v2.0.2.xsd)
- [SendStatement-v2.0.2.xsd](../xsd/SendStatement-v2.0.2.xsd)
- [SimpleTypes-v2.0.2.xsd](../xsd/SimpleTypes-v2.0.2.xsd)
- [TransportTypes-v2.0.2.xsd](../xsd/TransportTypes-v2.0.2.xsd)
