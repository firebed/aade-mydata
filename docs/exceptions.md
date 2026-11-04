# Exceptions

A call to myDATA can fail in two ways:

- Technical errors: the call failed. The library throws an exception.
- Business errors: the call worked (HTTP 200), but myDATA rejected a document. You read the errors from the response.

## Technical errors

```php
use Firebed\AadeMyData\Exceptions\MyDataAuthenticationException;
use Firebed\AadeMyData\Exceptions\MyDataConnectionException;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Exceptions\MyDataTimeoutException;
use Firebed\AadeMyData\Exceptions\RateLimitExceededException;
use Firebed\AadeMyData\Http\SendInvoices;

try {
    $responses = (new SendInvoices())->handle($invoices);
} catch (MyDataTimeoutException $e) {
    // No answer in time. myDATA may have stored the invoices. Don't resend blindly.
} catch (MyDataConnectionException $e) {
    // myDATA unreachable: no internet, server down, wrong endpoint
} catch (MyDataAuthenticationException $e) {
    // Missing or wrong credentials, or no access to this resource
} catch (RateLimitExceededException $e) {
    // Too many calls. Try again after $e->getRetryAfter() seconds.
} catch (MyDataException $e) {
    // Any other technical error
    $code = $e->getCode();
    $message = $e->getMessage();
}
```

Every exception thrown while talking to myDATA extends `MyDataException`. Catch only that one if you
don't need the details. Catch subclasses before their parents.

### Class hierarchy

All classes are in `Firebed\AadeMyData\Exceptions`.

```text
Exception
└── MyDataException
    ├── TransmissionFailedException
    │   ├── MyDataAuthenticationException
    │   ├── MyDataConnectionException
    │   ├── RateLimitExceededException
    │   └── ErrorResponseException
    ├── MyDataTimeoutException
    ├── InvalidResponseException
    ├── SchemaValidationException
    └── UnsupportedChannelException

InvalidArgumentException
└── InvalidAttributeException
```

### When each one is thrown

| Exception | Thrown when | Code |
|---|---|---|
| `TransmissionFailedException` | myDATA answered with an HTTP error that has no subclass below (e.g. 500). The message is the response body. | HTTP status |
| `MyDataAuthenticationException` | The credentials are not set (nothing is sent), or myDATA answered 401: wrong user id or subscription key, or the user has no access to the resource. The message has the reason myDATA gives, if any. | 401 |
| `MyDataConnectionException` | No response at all: DNS failure, refused connection, wrong endpoint, connect timeout. Retried by `withRetries()`. | 0 |
| `RateLimitExceededException` | myDATA answered 429. See [Rate limits](./installation#rate-limits). | 429 |
| `ErrorResponseException` | myDATA answered with an error document (`ResponseDoc` with errors) instead of the expected result, e.g. a search with a wrong parameter, or an HTTP 400 with errors. | HTTP status, or the code of the first error |
| `MyDataTimeoutException` | The request was sent but the answer took too long. Never retried, because myDATA may have processed it. | 28 |
| `InvalidResponseException` | The response body is empty. | 0 |
| `SchemaValidationException` | The document does not match the myDATA XSD and was not sent. Only with `Connection::withValidation()` or `MyDataRequest::setValidation()`. | 0 |
| `UnsupportedChannelException` | The request belongs to the other channel, e.g. `RequestDocs` on a provider connection. Nothing is sent. | 0 |
| `InvalidAttributeException` | A model setter got a value it cannot cast: an unknown enum value (`setVatCategory(99)`) or a wrong type. Not a `MyDataException`. | 0 |

### RateLimitExceededException

```php
use Firebed\AadeMyData\Exceptions\RateLimitExceededException;

try {
    $doc = (new RequestTransmittedDocs())->handle('0');
} catch (RateLimitExceededException $e) {
    $e->getRetryAfter(); // seconds to wait, or null if myDATA did not say
    $e->getRetryAt();    // Unix timestamp when calls are accepted again, or null
}
```

In a queue job, release the job until `getRetryAt()`. To wait and resend in place, use
`Connection::withRateLimitWait()`.

### ErrorResponseException

```php
use Firebed\AadeMyData\Exceptions\ErrorResponseException;

try {
    $doc = (new RequestDocs())->handle('0', dateFrom: $from, dateTo: $to);
} catch (ErrorResponseException $e) {
    foreach ($e->getErrors() as $error) {
        echo $error->getCode().': '.$error->getMessage();
    }
}
```

### SchemaValidationException

```php
use Firebed\AadeMyData\Exceptions\SchemaValidationException;
use Firebed\AadeMyData\Http\Connection;
use Firebed\AadeMyData\MyData;

$mydata = new MyData(Connection::make($userId, $subscriptionKey, 'dev')->withValidation());

try {
    $mydata->sendInvoices($invoices);
} catch (SchemaValidationException $e) {
    $e->getSchema(); // 'InvoicesDoc'
    $e->getErrors(); // [['field' => '{http://www.aade.gr/myDATA/invoice/v1.0}totalGrossValue', 'message' => '...']]
}
```

### MyDataTimeoutException

The invoice may be stored even though you got no answer. If you send it again, myDATA rejects it
with error 228 and names the invoice it already has. `Response::getDuplicate()` reads its MARK.
See [SendInvoices](./http/send-invoices).

## Business errors

Business errors come from failed business checks: wrong VAT number, wrong invoice totals, wrong income
or expense classifications, and so on. The call itself succeeded (HTTP 200), so nothing is thrown.
Each document gets its own `Response`. Check it:

```php
use Firebed\AadeMyData\Http\SendInvoices;

$responses = (new SendInvoices())->handle($invoices);

foreach ($responses as $response) {
    if ($response->isSuccessful()) {
        $mark = $response->getInvoiceMark();
        continue;
    }

    // $invoices[$response->getIndex() - 1] was rejected
    $status = $response->getStatusCode(); // e.g. 'ValidationError', 'TechnicalError', 'XMLSyntaxError'

    foreach ($response->getErrors() ?? [] as $error) {
        echo $error->getCode().': '.$error->getMessage();
    }
}
```

myDATA has more than 100 business error codes. See the official AADE myDATA documentation (PDF) for the full list.
