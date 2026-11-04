# SendInvoices

```shell
# production
https://mydatapi.aade.gr/myDATA/SendInvoices

# development
https://mydataapidev.aade.gr/SendInvoices

# production, provider channel
https://mydatapi.aade.gr/myDataProvider/SendInvoices

# development, provider channel
https://mydataapidev.aade.gr/myDataProvider/SendInvoices
```

Sends one or more invoices to myDATA. Works on the ERP and the provider channel.

```php
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\SendInvoices;
use Firebed\AadeMyData\Models\Invoice;

$invoice = new Invoice();

try {
    $responses = (new SendInvoices())->handle($invoice);
} catch (MyDataException $e) {
    echo "Connection error: " . $e->getMessage();
}
```

With the `MyData` client:

```php
use Firebed\AadeMyData\MyData;

$mydata = MyData::connect($userId, $subscriptionKey, 'dev');

$responses = $mydata->sendInvoices($invoice);
```

> [!NOTE]
> The examples send empty `Invoice` objects to stay short. A real invoice needs all its fields.
> See the [invoice examples](../invoices/examples).

## Parameters

`handle()` takes one argument, in one of these forms:

| Argument | Description |
|---|---|
| `Invoice` | One invoice |
| `Invoice[]` | A list of invoices |
| `InvoicesDoc` | A collection of invoices |

All classes are in `Firebed\AadeMyData\Models`.

### Many invoices

```php
$responses = (new SendInvoices())->handle([$invoice1, $invoice2, $invoice3]);
```

Or build an `InvoicesDoc`:

```php
use Firebed\AadeMyData\Models\InvoicesDoc;

$doc = new InvoicesDoc([new Invoice(), new Invoice(), new Invoice()]);
$doc->add(new Invoice());
$doc->add(new Invoice());

// 5 invoices
$responses = (new SendInvoices())->handle($doc);
```

> [!NOTE]
> If the request fails with a connection error, `MyDataException` is thrown and no invoice is sent.

## Results

`handle()` returns a `ResponseDoc`: a list with one [Response](../models/response-type) per invoice sent.

- `getIndex()` is the position of the invoice in the list you sent. It starts at 1, so the invoice is `$invoices[$index - 1]`.
- `isSuccessful()` is true when `getStatusCode()` is `Success`.
- On failure, `getErrors()` holds the errors.

```php
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\SendInvoices;
use Firebed\AadeMyData\Models\Invoice;

$invoices = [new Invoice(), new Invoice()];

try {
    $responses = (new SendInvoices())->handle($invoices);

    $errors = [];
    foreach ($responses as $response) {
        if ($response->isSuccessful()) {
            $index = $response->getIndex();          // $invoices[$index - 1]
            $uid = $response->getInvoiceUid();
            $mark = $response->getInvoiceMark();
            $qrUrl = $response->getQrUrl();
            $authCode = $response->getAuthenticationCode(); // provider channel only

            // Save uid, mark and qrUrl with your own invoice.
        } else {
            foreach ($response->getErrors() as $error) {
                $errors[$response->getIndex()][] = $error->getCode() . ': ' . $error->getMessage();
            }
        }
    }
} catch (MyDataException $e) {
    // Connection error. No invoice was sent.
    echo "Connection error: " . $e->getMessage();
}
```

> [!CAUTION]
> When you send many invoices at once, check each response. Some invoices can succeed while others fail.

## Sent twice

If an answer is lost (e.g. a timeout) and you send the same invoice again, myDATA rejects it with error 228.
`getDuplicate()` reads the MARK of the invoice myDATA already holds:

```php
$response = $responses->first();

if ($duplicate = $response->getDuplicate()) {
    $duplicate->mark;
    $duplicate->authenticationCode; // null until myDATA has stored the invoice
    $duplicate->toResponse();       // the successful Response it stands for
}
```
