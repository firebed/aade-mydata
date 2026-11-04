# ΑΑΔΕ - AADE myDATA

[![Latest Version on Packagist](https://img.shields.io/packagist/v/firebed/aade-mydata.svg?style=flat-square)](https://packagist.org/packages/firebed/aade-mydata)
[![Total Downloads](https://poser.pugx.org/firebed/aade-mydata/downloads)](https://packagist.org/packages/firebed/aade-mydata)
![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/firebed/aade-mydata/php.yml)
[![PHP Version Require](https://poser.pugx.org/firebed/aade-mydata/require/php)](https://packagist.org/packages/firebed/aade-mydata)
[![License](https://poser.pugx.org/firebed/aade-mydata/license)](LICENSE.md)

## Support This Project

If you find this project useful, you can show your appreciation and support by giving it a ⭐. Your support motivates us to work harder and make even better and more useful tools!

## Introduction

This package provides an expressive, fluent interface to ΑΑΔΕ myDATA invoicing REST API. It handles all the boilerplate code for sending, cancelling and requesting invoices.

## Documentation

- Official documentation is available 👉 [on our documentation site](https://docs.invoicemaker.gr/getting-started)
- myDATA webpage: [AADE myDATA](https://www.aade.gr/mydata)
- ERP myDATA documentation (PDF): [AADE ERP myDATA REST API v2.0.2](https://www.aade.gr/sites/default/files/2026-09/myDATA%20API%20Documentation%20v2.0.2_official_erp_1.pdf)
- Provider myDATA documentation (PDF): [AADE Provider myDATA REST API v2.0.2](https://www.aade.gr/sites/default/files/2026-09/myDATA%20API%20Documentation_Providers_v2%200%202_official_0.pdf)

## Requirements

To use this package, you will need first a `aade id` and a `Subscription key`. You can get these credentials by signing up to mydata rest api.

- Development: [Sign up to mydata development api](https://mydata-dev-register.azurewebsites.net/)
- Production: [Sign up to mydata production api](https://www.aade.gr/mydata)
- guzzlehttp/guzzle ^7.8 || ^8.0

| Version | PHP | myDATA  | Support |
|---------|-----|---------|---------|
| ^v6.x   | 8.3 | v2.0.2  | Active  |
| ^v5.x   | 8.1 | v2.0.2  | Security fixes |
| ^v5.x   | 8.1 | v2.0.1  | Ended   |
| ^v5.x   | 8.1 | v1.0.12 | Ended   |
| ^v4.x   | 8.1 | v1.0.8  | Ended   |
| ^v3.x   | 8.1 | v1.0.7  | Ended   |
| ^v2.x   | 8.1 | v1.0.5  | Ended   |
| ^v1.x   | 8.0 | v1.0.3  | Ended   |

## Installation

To install through Composer, run the following command:

```
composer require firebed/aade-mydata
```

## Upgrading

Upgrading from 5.x? Read the [upgrade guide](docs/upgrade-guide.md). If a coding assistant does the
upgrade for you, hand it the [AI upgrade guide](docs/upgrade-guide-ai.md), which lists every rename
and behaviour change with find/replace rules.

## Setup

Once you have the user id and the subscription key, use the following code to set the environment and the credentials:

```php
$env = "dev"; // For production use "prod"
$user_id = "your-user-id";
$subscription_key = "your-subscription-key";

MyDataRequest::setEnvironment($env);
MyDataRequest::setCredentials($user_id, $subscription_key);
```

For development, you may need to disable client verification if you are not using https:

```php
MyDataRequest::verifyClient(false);
```

### Connections and the `MyData` client

The static setters above configure the process-wide default connection. When one process serves
several tenants, or you simply prefer an object, build a `Connection` and a `MyData` client on it.
A connection is immutable, keeps its own Guzzle client and carries the retry policy:

```php
use Firebed\AadeMyData\Http\Connection;
use Firebed\AadeMyData\MyData;

$mydata = MyData::connect($user_id, $subscription_key, 'prod');

// or fully configured
$mydata = new MyData(
    Connection::make($user_id, $subscription_key, 'prod')
        ->asProvider()          // myDataProvider channel
        ->withTimeout(30)
        ->withRetries(3)        // retry 429 / 502 / 503 / 504 / connection failures with backoff
);

$responses = $mydata->sendInvoices($invoices);
$xml = $mydata->lastRequest()->getRequestXml();

// Any request can be bound to a connection
(new SendInvoices())->using($connection)->handle($invoices);
```

Retries are also available on the default connection with `MyDataRequest::setRetries(3)`. Timeouts
are never retried, because myDATA may have processed the request already.

### Pagination

Requests that page their results (`RequestDocs`, `RequestTransmittedDocs`, `RequestMyIncome`,
`RequestMyExpenses`, `RequestVatInfo`, `RequestE3Info`) can follow the continuation token for you:

```php
foreach ((new RequestTransmittedDocs())->invoices(mark: '0') as $invoice) {
    // every Invoice of every page, fetched lazily
}

foreach ((new RequestDocs())->pages(mark: '0', dateFrom: '01/01/2026', dateTo: '31/01/2026') as $page) {
    // a RequestedDoc per page
}
```

## Transmitting through a provider (Gateway)

ΑΑΔΕ is retiring the ERP transmission channel: invoices have to be transmitted through a
licensed e-invoicing provider. Since 5.11 every request travels through a `Gateway`, so you
can switch the transport without changing the code that builds and sends your invoices.

To transmit through the **Oxygen provider**, install the ready-made gateway,
[oxygensuite/aade-mydata-oxygen](https://github.com/oxygensuite/aade-mydata-oxygen), and
register your provider token next to your existing setup:

```shell
composer require oxygensuite/aade-mydata-oxygen
```

```php
use Firebed\AadeMyData\Http\MyDataRequest;
use OxygenSuite\AadeMyData\OxygenProvider;

// Your AADE setup stays: the requests that remain on the ERP channel still use it.
MyDataRequest::setEnvironment($env);
MyDataRequest::setCredentials($user_id, $subscription_key);

// From now on SendInvoices, CancelInvoice and SendPaymentsMethod go through the provider.
OxygenProvider::register(token: 'your-company-api-token');
```

Everything else — requesting documents, classifications, and so on — keeps talking to
ΑΑΔΕ directly. The bridge's README documents what is routed, the responses you get back,
and the attributes a provider needs that myDATA has no field for: the issue time
(`setIssueTime()`, required), the unit price (`setUnitPrice()`), the line discount
(`setDiscount()`) and free extra fields.

To write a gateway for another provider, see [Custom gateway](docs/getting-started.md#custom-gateway).

### Rate limits

myDATA throttles each subscription key by the **number of calls in a window of about five
minutes**, not by calls per second. Measured on the sandbox in November 2026: `RequestDocs`
and `RequestTransmittedDocs` share one counter of **11 calls per 300-second window** (measured twice,
with and without spacing between calls), after which
every call to either of them answers HTTP 429 with a `Retry-After` header and the body
`{"statusCode":429,"message":"Rate limit is exceeded. Try again in 298 seconds."}` until the
window ends. `RequestVatInfo`, `RequestMyIncome` and `RequestE3Info` kept answering while the
document requests were throttled, so the counters are per operation group. The production
limits are not published and may differ; the library reads whatever `Retry-After` says.

What the library does with a 429:

- **Short waits are retried** (`withRetries()`): a `Retry-After` of up to 10 seconds is waited
  out like any other retry. A longer one is **not** retried blindly: the request fails with
  `RateLimitExceededException`, whose `getRetryAfter()` / `getRetryAt()` say when the key is
  accepted again, so a queue job can release itself until then.
- **`withRateLimitWait(600)`** sits out a `Retry-After` of up to the given seconds and sends the
  request again. For a sync job in a worker, waiting five minutes beats failing.
- **`withThrottle(28000)`** keeps at least that many milliseconds between two requests of the
  connection, which keeps a long run under the window in the first place.

```php
$connection = Connection::make($user_id, $subscription_key, 'prod')
    ->withThrottle(28_000)        // one document call every 28 s stays under ~11 per 5 min
    ->withRateLimitWait(600);     // and if myDATA still says "try again in N seconds", wait

foreach ((new RequestTransmittedDocs)->using($connection)->invoices(mark: $lastMark) as $invoice) {
    $lastMark = max($lastMark, (int) $invoice->getMark());
}
```

Keep the last mark you processed and ask for documents after it: that is the single
biggest saver of calls. If you stop on the exception instead, resume later with the last
page's continuation token: `pages(..., nextPartitionKey: $token->getNextPartitionKey(),
nextRowKey: $token->getNextRowKey())`. The static equivalents are `MyDataRequest::setThrottle()`
and `MyDataRequest::setRateLimitWait()`.

### Validation before sending

`withValidation()` checks the XML of every request that sends a document (`SendInvoices`, the
classifications, `SendPaymentsMethod`, the delivery note calls, `SendStatement`) against the
myDATA XSD before it is sent. An invalid document throws `SchemaValidationException`, which
names the failing fields, instead of costing a call that myDATA answers with an error:

```php
use Firebed\AadeMyData\Exceptions\SchemaValidationException;

$mydata = new MyData(Connection::make($user_id, $subscription_key, 'prod')->withValidation());

try {
    $mydata->sendInvoices($invoices);
} catch (SchemaValidationException $e) {
    $e->getErrors(); // [['field' => '{http://www.aade.gr/myDATA/invoice/v1.0}totalGrossValue', 'message' => "The value '-10' is less than …"]]
}
```

Validation is off by default; `MyDataRequest::setValidation()` turns it on for the default connection.

### Logging and listeners

`withLogger()` writes every request of a connection to a PSR-3 logger (Monolog, Laravel's `Log`, …):
an answered request at `info`, a retry or a throttled request at `warning`, any other failure at
`error`. The credentials are never logged. The request and response XML and the VAT numbers of the
query, which identify taxpayers, are logged only with `withBodies: true`. Calling `withLogger()` again
replaces the logger; `withLogger(null)` stops logging.

```php
$connection = Connection::make($user_id, $subscription_key, 'prod')
    ->withRetries(3)
    ->withLogger($logger);
```

For an audit trail or metrics, listen to the requests yourself. `afterSending()` is called once per
request, after its retries, with the request and response XML, or the exception it failed with,
and the time it took. `beforeRetry()` is called before each retry:

```php
use Firebed\AadeMyData\Http\Exchange;
use Firebed\AadeMyData\Http\Retry;

$connection = Connection::make($user_id, $subscription_key, 'prod')
    ->afterSending(fn (Exchange $e) => AuditLog::create([
        'action' => $e->action(),          // 'SendInvoices'
        'request' => $e->requestBody,      // the XML sent
        'response' => $e->responseBody,    // the XML myDATA answered, null on failure
        'error' => $e->exception?->getMessage(),
        'seconds' => $e->duration,
    ]))
    ->beforeRetry(fn (Retry $r) => $r->rateLimited() && Metrics::increment('mydata.throttled'));
```

Listeners add up and also run for faked requests (see [Testing](#testing)). A listener that throws is
skipped: a broken audit log never turns an invoice myDATA accepted into a failure you would send
again. A document rejected by `withValidation()` is never sent, so listeners don't see it.

Give a listener a key when the code that registers it can run more than once (a worker's boot, a
queue job): a listener with the same key replaces it instead of adding up. Remove listeners by key or
by the listener itself:

```php
$connection = $connection->afterSending($audit, key: 'audit');  // registered once, however often this runs

$connection->withoutListener('audit');   // by key, from afterSending() and beforeRetry()
$connection->withoutListener($audit);    // by the same closure or callable
$connection->withoutListeners();         // every listener; the logger stays (withLogger(null) removes it)
```

The static equivalents are `MyDataRequest::setLogger()`, `afterSending()`, `beforeRetry()`,
`removeListener()` and `removeListeners()`.

## Send invoice example

```php
use Firebed\AadeMyData\Http\SendInvoices;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Exceptions\MyDataException;

// Prepare your invoices, for simplicity we will use an array of empty
// Invoice objects. You should populate these objects with your own data.
$invoices = [new Invoice(), new Invoice()];
$sender = new SendInvoices();

try {
    $responses = $sender->handle($invoices);
    
    $errors = [];
    foreach ($responses as $response) {
        if ($response->isSuccessful()) { 
            // This invoice was successfully sent to myDATA.     
            // Each response has an index value which corresponds
            // to the index (-1) of the $invoices array.
            
            $index = $response->getIndex();
            $uid = $response->getInvoiceUid();
            $mark = $response->getInvoiceMark();
            $cancelledByMark = $response->getCancellationMark();
            $qrUrl = $response->getQrUrl();
    
            // If you need to relate the response to your local invoice
            // $invoice = $invoices[$index - 1];    
    
            print_r(compact('index', 'uid', 'mark', 'cancelledByMark', 'qrUrl'));
        } else {
            // There were some errors for a specific invoice. See errors for details.
            foreach ($response->getErrors() as $error) {
                $errors[$response->getIndex() - 1][] = $error->getCode() . ': ' . $error->getMessage();
            }
        }
    }
} catch (MyDataException $e) {
    // There was a communication error. None of the invoices were sent.
    echo "Σφάλμα επικοινωνίας: " . $e->getMessage();
}
```

## Available methods

| Method                                                                                      | Availability       |
|---------------------------------------------------------------------------------------------|--------------------|
| [Validate VAT Number](http://docs.invoicemaker.gr/http/search-vat)                          | :white_check_mark: |
| [SendInvoices](http://docs.invoicemaker.gr/http/send-invoices)                              | :white_check_mark: |
| [CancelInvoice](http://docs.invoicemaker.gr/http/cancel-invoice)                            | :white_check_mark: |
| [RequestDocs](http://docs.invoicemaker.gr/http/request-docs)                                | :white_check_mark: |
| [RequestTransmittedDocs](http://docs.invoicemaker.gr/http/request-transmitted-docs)         | :white_check_mark: |
| [RequestMyIncome](http://docs.invoicemaker.gr/http/request-my-income)                       | :white_check_mark: |
| [RequestMyExpenses](http://docs.invoicemaker.gr/http/request-my-expenses)                   | :white_check_mark: |
| [RequestVatInfo](http://docs.invoicemaker.gr/http/request-vat-info)                         | :white_check_mark: |
| [RequestE3Info](http://docs.invoicemaker.gr/http/request-e3-info)                           | :white_check_mark: |
| [SendPaymentsMethod](http://docs.invoicemaker.gr/http/send-payments-method)                 | :white_check_mark: |
| [SendIncomeClassification](http://docs.invoicemaker.gr/http/send-income-classification)     | :white_check_mark: |
| [SendExpensesClassification](http://docs.invoicemaker.gr/http/send-expenses-classification) | :white_check_mark: |
| [CancelDeliveryNote](http://docs.invoicemaker.gr/http/cancel-delivery-note) (Για παρόχους)  | :white_check_mark: |
| [CancelReceivingNote](http://docs.invoicemaker.gr/http/cancel-receiving-note) (Για παρόχους) | :white_check_mark: |

## Digital Goods Movement (Ψηφιακή Διακίνηση Αγαθών)

Digital Goods Movement methods allow real-time tracking and management of goods in transit. These methods are available **only for the ERP channel** and are **not** supported by invoice service providers.

| Method                                                                                                         | Description                  |
|----------------------------------------------------------------------------------------------------------------|------------------------------|
| [RegisterTransfer](http://docs.invoicemaker.gr/http/digital-goods-movement#registertransfer)                   | Register a transfer          |
| [ConfirmDeliveryOutcome](http://docs.invoicemaker.gr/http/digital-goods-movement#confirmdeliveryoutcome)       | Confirm delivery outcome     |
| [ConfirmDeliveryReturn](http://docs.invoicemaker.gr/http/digital-goods-movement#confirmdeliveryreturn)         | Confirm delivery return      |
| [RejectDeliveryNote](http://docs.invoicemaker.gr/http/digital-goods-movement#rejectdeliverynote)               | Reject a delivery note       |
| [RequestDeliveryNoteStatus](http://docs.invoicemaker.gr/http/digital-goods-movement#requestdeliverynotestatus) | Request delivery note status (by mark or qrUrl) |
| [GenerateGroupQrCode](http://docs.invoicemaker.gr/http/digital-goods-movement#generategroupqrcode)             | Generate group QR code       |
| [RequestGroupQrDetails](http://docs.invoicemaker.gr/http/digital-goods-movement#requestgroupqrdetails)         | Request group QR details     |

### Quick example

```php
use Firebed\AadeMyData\Http\DigitalGoodsMovement\RegisterTransfer;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\Transport;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\TransportDetails;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\Location;
use Firebed\AadeMyData\Enums\DigitalGoodsMovement\TransportType;

$details = new TransportDetails();
$details->setVehicleNumber('AHN0011');
$details->setTransportType(TransportType::PRIVATE_USE_TRUCK);
$details->setCarrierVatNumber('777777777');
$details->setLocation(new Location(41.303921, -81.901693));

$transport = new Transport();
$transport->setQrUrl('https://mydataapidev.aade.gr/TimologioQR/QRInfo?q=test_qr_url');
$transport->setTransportDetail($details);

$request = new RegisterTransfer();
$response = $request->handle($transport);
```

For detailed documentation and examples of all methods, see the [Digital Goods Movement documentation](http://docs.invoicemaker.gr/http/digital-goods-movement).

## Digital Client (Car rental, parking/wash, workshops)

The **Digital Client** API is specifically designed to serve businesses in industries such as car rentals, parking services, vehicle washing and workshops.

Please refer to the [ΑΑΔΕ Digital Client](https://github.com/oxygensuite/digital-client-list), developed by [Oxygen Invoicing Provider](https://github.com/oxygensuite/).
It provides a robust, seamless and complete solution for managing customer and vehicle data while enabling smooth and uninterrupted data exchange between ERP systems and Greece's Independent Authority for Public Revenue (Ανεξάρτητη Αρχή Δημοσίων Εσόδων, ΑΑΔΕ).

## Upgrade Guide

If you are upgrading from a previous version, please see [upgrade guide](docs/upgrade-guide.md)

## Testing

### Faking myDATA in your application's tests

`MyData::fake()` answers every request with a fake response instead of sending it, whatever
connection the request uses, and records it for assertions:

```php
use Firebed\AadeMyData\MyData;
use Firebed\AadeMyData\Http\SendInvoices;
use Firebed\AadeMyData\Http\RequestDocs;
use Firebed\AadeMyData\Testing\FakeGateway;

$fake = MyData::fake();

$app->issueInvoice($order);   // SendInvoices succeeds with mark 400000000000001

$fake->assertSent(SendInvoices::class, fn (SendInvoices $request) => $request->getInvoicesDoc()->count() === 1);
$fake->assertSentCount(1);

MyData::stopFaking();         // e.g. in tearDown()
```

Without a response, the requests that send documents and the cancellations succeed. Each document
gets a mark, numbered from 400000000000001, and an invoice also gets its uid. The searches find
nothing. Give the response per request class:

```php
MyData::fake([
    SendInvoices::class => FakeGateway::rejected(101, 'Invalid VAT number'),      // a ValidationError
    RequestDocs::class => FakeGateway::sequence($page1, $page2),                    // one per call
    RequestVatInfo::class => new RateLimitExceededException(retryAfter: 300),      // thrown
    CancelInvoice::class => fn (CancelInvoice $request, RecordedRequest $sent) => FakeGateway::accepted(),
    '*' => file_get_contents('stubs/response.xml'),                                 // any other request
]);
```

A response can be an XML string, a model (`ResponseDoc`, `Response`, `RequestedDoc`, …), a list
of `Response` models (one per document) or of `Invoice` models (a `RequestedDoc`), an exception,
a closure returning any of these, or a `FakeGateway::sequence()`. Requests with no default
response (the delivery note and statement calls) need one, so no request can slip through to myDATA.

### Running the package's tests

```shell
composer test
```

## Contributing

Please see [CONTRIBUTING](http://docs.invoicemaker.gr/contributing) for details.

## Licence

AADE myDATA is licenced under the [MIT License](LICENSE.md).

Copyright 2022 &copy; Okan Giritli
