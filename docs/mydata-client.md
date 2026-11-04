# MyData client

`MyData` is a client bound to one `Connection`. Use it when one app serves many businesses (tenants),
or when you prefer an object over the static `MyDataRequest::init()` setup.

```php
use Firebed\AadeMyData\MyData;

$mydata = MyData::connect($userId, $subscriptionKey, 'dev');

$responses = $mydata->sendInvoices($invoice);
```

`MyData::connect()` and `Connection::make()` use the sandbox (`'dev'`) unless you pass `'prod'`.

## Configure the connection

Build a `Connection`, chain what you need, and pass it to the client:

```php
use Firebed\AadeMyData\Http\Connection;
use Firebed\AadeMyData\MyData;

$connection = Connection::make($userId, $subscriptionKey, 'dev')
    ->withTimeout(30)
    ->withConnectionTimeout(5)
    ->withRetries(3)
    ->withValidation()
    ->withLogger($logger);

$mydata = new MyData($connection);
```

A connection is immutable. Every `with*()` method returns a changed copy and leaves the original as it was.

| Method | Does |
|---|---|
| `withCredentials($userId, $subscriptionKey)` | Other credentials |
| `withEnvironment('dev')` | `'dev'`, `'prod'` or an `Environment` case |
| `asProvider()` | Provider channel. `asProvider(false)` goes back to ERP |
| `withTimeout(30)` | Seconds for the whole request |
| `withConnectionTimeout(5)` | Seconds to connect |
| `withVerify(false)` | SSL check: `true`, `false` (dev only) or the path of a CA bundle |
| `withRequestOption('proxy', $url)` | One Guzzle request option |
| `withRequestOptions([...])` | Replaces all Guzzle request options |
| `withRetries(3, 500)` | Retries on 429, 502, 503, 504 and connection failures. See [Retries](installation#retries) |
| `withThrottle(28_000)` | Minimum ms between two calls. See [Rate limits](installation#rate-limits) |
| `withRateLimitWait(600)` | Waits out a 429 for up to this many seconds |
| `withValidation()` | Checks the XML against the XSD before sending. See [Validation](installation#validation-before-sending) |
| `withLogger($logger)` | PSR-3 logger. See [Logging](installation#logging-and-listeners) |
| `afterSending($listener, $key)` | Called after every request |
| `beforeRetry($listener, $key)` | Called before every retry |
| `withoutListener($key)`, `withoutListeners()` | Removes listeners |
| `withGateway($gateway)` | Sends through your own `Gateway` |
| `withHandler($stack)` | Guzzle handler stack, e.g. a `MockHandler` in tests |

## Many tenants

Build one base connection with the shared settings. Add each tenant's credentials on top of it.

```php
use Firebed\AadeMyData\Http\Connection;
use Firebed\AadeMyData\MyData;

$base = Connection::make('', '', 'dev')
    ->withTimeout(30)
    ->withRetries(3)
    ->withLogger($logger);

function mydataFor(Tenant $tenant, Connection $base): MyData
{
    $connection = $base
        ->withCredentials($tenant->mydata_user, $tenant->mydata_key)
        ->asProvider($tenant->uses_provider);

    return new MyData($connection);
}

mydataFor($tenantA, $base)->sendInvoices($invoicesA);
mydataFor($tenantB, $base)->sendInvoices($invoicesB);
```

> [!WARNING]
> Don't use `MyDataRequest::init()` or the other static setters for tenants. They change the default
> connection of the whole process, so in a queue worker the next job could send with the previous tenant's key.

> [!NOTE]
> Call `withThrottle()` after `withCredentials()`. Connections copied from one connection share its throttle,
> but myDATA counts calls per subscription key.

## Both channels

`with()` returns a new client on a changed connection. The first client stays as it was.

```php
use Firebed\AadeMyData\Http\Connection;

$erp = MyData::connect($userId, $subscriptionKey, 'dev');
$provider = $erp->with(fn (Connection $c) => $c->asProvider());

$doc = $erp->requestDocs(mark: '1234567890');
$responses = $provider->sendInvoices($invoice);
```

A request on the wrong channel throws `UnsupportedChannelException` and nothing is sent.
For example, `requestDocs()` is ERP only and `requestProviderDocs()` is provider only.

## Request and response XML

`lastRequest()` returns the request object of the last call:

```php
$responses = $mydata->sendInvoices($invoice);

$requestXml = $mydata->lastRequest()->getRequestXml();
$responseXml = $mydata->lastRequest()->getResponseXML();
```

## Any request

`request()` binds a request to the client's connection. Use it for paging, or for a request the client
has no method for:

```php
use Firebed\AadeMyData\Http\RequestDocs;

foreach ($mydata->request(new RequestDocs())->invoices(mark: '0') as $invoice) {
    // ...
}
```

Without a client, pass the connection to the request with `using()`:

```php
use Firebed\AadeMyData\Http\SendInvoices;

$responses = (new SendInvoices())->using($connection)->handle($invoice);
```

## Read the connection

```php
$connection = $mydata->connection();

$connection->isProvider();      // bool
$connection->channel();         // Channel::Erp or Channel::Provider
$connection->isProduction();    // bool
$connection->environment();     // Environment::Development or Environment::Production
$connection->hasCredentials();  // false if the user ID or the key is empty
$connection->baseUrl();         // the myDATA URL it sends to
```

## Methods

| Invoices | Reports | Providers | Delivery notes and statements |
|---|---|---|---|
| `sendInvoices` | `requestDocs` | `requestProviderDocs` | `cancelDeliveryNote` |
| `cancelInvoice` | `requestTransmittedDocs` | `requestReceiverInfo` | `cancelReceivingNote` |
| `sendIncomeClassification` | `requestMyIncome` | `sendUnsignedInvoices` | `registerTransfer` |
| `sendExpensesClassification` | `requestMyExpenses` | | `confirmDeliveryOutcome` |
| `sendPaymentsMethod` | `requestVatInfo` | | `confirmDeliveryReturn` |
| | `requestE3Info` | | `rejectDeliveryNote` |
| | | | `requestDeliveryNoteStatus` |
| | | | `requestDeliveryNoteStatusByQrUrl` |
| | | | `generateGroupQrCode` |
| | | | `requestGroupQrDetails` |
| | | | `sendStatement`, `requestStatements`, `recallStatement` |

Each method takes the same parameters as the `handle()` of its request. See the pages under Requests.

For tests, `MyData::fake()` answers every request with fake responses. See [Testing](testing).
