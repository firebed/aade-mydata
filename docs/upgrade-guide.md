# Upgrade guide

## Upgrade guide from 5.x to 6.x

> [!TIP]
> If an AI assistant (Claude, Copilot, Cursor, ChatGPT) does the upgrade, give it the
> [AI upgrade guide](./upgrade-guide-ai). It lists every rename and every behaviour change,
> with find/replace rules and before/after examples.

### Update composer

Version 6 needs PHP 8.3 or newer.

```json
{
  "require": {
    "firebed/aade-mydata": "^6.0"
  }
}
```

### Breaking changes

- **PHP ^8.3.** PHP 8.1 and 8.2 are no longer supported.
- **Guzzle ^7.8 || ^8.0.** Guzzle 8 is supported. Guzzle 7 stays supported until the next major version.
- **Renames.** `Models\Type` → `Models\Model`, `Models\TypeArray` → `Models\ModelCollection`,
  enum `Enums\PaymentMethod` → `Enums\PaymentType` (the model `Models\PaymentMethod` keeps its name).
  The docs folder `types` is now `models`.
- **Descriptive enum case names.** For example `PaymentType::CASH` instead of `PaymentType::METHOD_3`,
  `TaxType::WITHHELD_TAX` instead of `TaxType::TYPE_1`. The old names are removed. `InvoiceType` keeps
  `TYPE_1_1`, `TYPE_1_2`, .... The backing values did not change. The "Enum" column in the
  [Appendix](./appendix/invoice-types) pages shows the new name of each code, and the
  [AI guide](./upgrade-guide-ai) has the full old → new table.
- **Model casts.** The `protected array $casts` property is now a `casts()` method, and lists are declared
  with `Arr::of()`. The base class now collects repeated XML elements by itself, so all `set()` overrides
  are removed. This only matters if you extend the package's models:

  ```php
  use Firebed\AadeMyData\Casts\Arr;

  protected function casts(): array
  {
      return [
          'invoiceHeader'      => InvoiceHeader::class,
          'invoiceDetails'     => Arr::of(InvoiceDetails::class),
          'correlatedInvoices' => Arr::of('int'),
          'invoiceType'        => InvoiceType::class,
      ];
  }
  ```

  A `ModelCollection` no longer takes the child name in its constructor. The single `Arr::of()` entry
  of `casts()` declares it.
- **Wrong values throw.** A value that is not a case of the enum (e.g. `setVatCategory(99)`), or a wrong
  type for a cast, throws `Firebed\AadeMyData\Exceptions\InvalidAttributeException`. Before, it was stored
  as `null` without a warning. Reading XML from myDATA is not affected: the readers run in
  `Model::lenient()` and unknown values are read as `null` as before.
- **Typed values after reading XML.** Getters return `int`/`float`/`bool` as their return type says
  (e.g. `getIndex()` returns `1`, not `'1'`). The same goes for `toArray()`/`toJson()`.
  Check `===` comparisons against strings.
- **An empty string means "no value"** on every cast attribute (`setClassificationType('')` stores `null`).
- **`Invoice::getTaxesTotals()` returns `null`** when no taxes totals were added. Before, it returned an
  empty `TaxesTotals` that was not attached to the invoice.
- **Empty wrapper elements are not written** to the XML (e.g. no `<paymentMethods/>`). The XML is no longer
  pretty-printed by default (`getRequestXml()` / `getResponseXml()` format it on demand).
- **`TaxTotals::getTaxCategory()` returns `?int`** (the category code), and `HasLabels::labels()` returns
  `[value => label]`.
- **`Connection::make()` / `MyData::connect()` default to the sandbox** unless you pass `'prod'`.
- **`RequestDocs` and `RequestTransmittedDocs` are ERP only.** On a provider connection they throw
  `UnsupportedChannelException` (they sent the ERP parameters and read the wrong document). Providers use
  the new [`RequestProviderDocs`](./http/request-provider-docs).
- **Request classes no longer extend each other.** `SendInvoices` and `SendUnsignedInvoices` share the base
  `InvoicesDocRequest`. `CancelInvoice`, `CancelDeliveryNote` and `CancelReceivingNote` share
  `CancellationRequest`. `instanceof SendInvoices` no longer matches `SendUnsignedInvoices` (and
  `instanceof CancelInvoice` no longer matches the note cancellations). Check the base class instead.
- **Removed:** `TransportDetail` and `Invoice::getOtherTransportDetails()/addOtherTransportDetail()/setOtherTransportDetails()`
  (dropped by myDATA 2.0.1), `InvoiceSummary::getTotalInformationalTaxAmount()/setTotalInformationalTaxAmount()`,
  `WithheldPercentCategory::affectsTotalGrossValue()`, the `HasIterator` trait (`RequestedBookInfo` is no
  longer iterable, use `getBookInfo()`).
- **`MyDataRequest`.** The static setup (`init()`, `setCredentials()`, `setEnvironment()`, `verifyClient()`,
  `setTimeout()`, `setConnectionTimeout()`, `setRequestOptions()`, `setGateway()`, `setHandler()`) works as
  before, but now configures a `Connection` object (`MyDataRequest::defaultConnection()`). Code that read
  the private static properties through reflection must change. The environment accepts only `dev`/`prod`
  (or the `Environment` enum).

### Features

#### Connection per tenant and client

```php
use Firebed\AadeMyData\Http\Connection;
use Firebed\AadeMyData\MyData;

$mydata = MyData::connect($userId, $subscriptionKey, 'dev');

// or fully configured
$mydata = new MyData(
    Connection::make($userId, $subscriptionKey, 'dev')
        ->asProvider()
        ->withTimeout(30)
        ->withRetries(3)
);

$response = $mydata->sendInvoices($invoice);
$xml = $mydata->lastRequest()->getRequestXml();

// Any request can use a connection
(new SendInvoices)->using($connection)->handle($invoice);
```

Each `Connection` is immutable (`with*()` returns a copy) and keeps its own Guzzle client, created once and reused.

#### Automatic retries

```php
MyDataRequest::setRetries(3, 500);               // static setup
Connection::make(...)->withRetries(3, 500);      // per connection
```

Calls that fail with 429, 502, 503, 504 or a connection failure are retried. The delay doubles on each
attempt, and `Retry-After` is respected. Timeouts are never retried, because myDATA may have processed the request.

#### Rate limits

myDATA limits calls per window of about five minutes (see [Installation › Rate limits](./installation#rate-limits)).
`RateLimitExceededException` now has `getRetryAfter()` / `getRetryAt()`, and connections accept
`withThrottle(ms)` and `withRateLimitWait(seconds)` (static: `setThrottle()`, `setRateLimitWait()`).

#### Automatic pagination

```php
foreach ((new RequestDocs)->pages(mark: '0', dateFrom: '01/01/2026', dateTo: '31/01/2026') as $page) {
    // a RequestedDoc per page, the continuationToken is followed for you
}

foreach ((new RequestTransmittedDocs)->invoices('0') as $invoice) {
    // every Invoice of every page
}
```

Also on `RequestMyIncome`, `RequestMyExpenses`, `RequestVatInfo`, `RequestE3Info` (`pages()`).

#### Models

- `Model::fromArray()`, `toJson()`, `JsonSerializable`, `unset()`, `cast()`, `Model::lenient()`.
- `ModelCollection::last()`, `map()`, `filter()`, `childKey()`, `$collection[] = $item`.

#### Providers

New provider requests: [`RequestProviderDocs`](./http/request-provider-docs) (MARK, UID and authentication
code of the invoices the provider sent for an issuer), [`RequestReceiverInfo`](./http/request-receiver-info)
and [`SendUnsignedInvoices`](./http/send-unsigned-invoices). `Response::getDuplicate()` reads error 228
(the invoice was already sent). It returns the MARK and, once stored, the authentication code of the
invoice myDATA already holds.

#### Testing with `MyData::fake()`

In your app's tests, `MyData::fake()` answers every request with a fake response, without reaching
myDATA, and records it for `assertSent()`, `assertSentCount()` and so on. See [Testing](./testing).

#### Validation before sending

`Connection::withValidation()` (static: `MyDataRequest::setValidation()`) checks the XML of every send
against the myDATA XSD before it leaves. An invalid document throws `SchemaValidationException` with the failing fields.

#### Logging

`Connection::withLogger($logger)` writes every call, retry and failure to a PSR-3 logger.
`afterSending()` / `beforeRetry()` pass every call and retry to your own code (e.g. for an audit log).

#### Tooling

The package passes **PHPStan (level 7)** and **Laravel Pint** in CI (`composer analyse`, `composer lint`).

## Upgrade guide within 5.x (Gateway)

### Breaking changes

- `MyDataRequest::handleTransmissionException()` (`protected`) moved to `GuzzleGateway`.
  Requests no longer map Guzzle exceptions themselves. The gateway that carried the request does.
  If you subclassed a request to override it, move that logic into a `Gateway` implementation
  (extending `GuzzleGateway` is enough) and register it with `MyDataRequest::setGateway()`.

## Upgrade guide from 4.x to 5.x

### Update composer

```json
{
  "require": {
    "firebed/aade-mydata": "^5.0"
  }
}
```

### Breaking changes

- Removed the vat-registry dependency. To search VAT numbers, run `composer require firebed/vat-registry`.
  See the [vat-registry](https://github.com/firebed/vat-registry) docs. If you don't search VAT numbers,
  ignore this change.
- Removed the ext-soap dependency.

### Features

#### Squash invoice rows

`$invoice->squashInvoiceRows()`

Providers and ERPs only have to send a summary of the invoice lines and classifications, not every line.
See [Squashing invoice rows](./squashing-invoice-rows).

#### Validate invoices

`$invoice->validate()`

#### Preview the invoice XML

`$invoice->toXml()`

#### Classification combinations

```php
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Services\Classifications;

Classifications::incomeClassifications(InvoiceType::TYPE_1_1);
Classifications::incomeClassifications(InvoiceType::TYPE_1_1)->toKeyLabel();
Classifications::incomeClassificationExists('1.1', 'category1_1', 'E3_561_001'); // true
```

See [Classifications](./classifications).

#### Labels on all enums

```php
use Firebed\AadeMyData\Enums\CountryCode;
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Enums\PaymentType;
use Firebed\AadeMyData\Enums\VatCategory;

echo InvoiceType::TYPE_1_1->label();
// Τιμολόγιο Πώλησης

echo InvoiceType::TYPE_1_2->label();
// Τιμολόγιο Πώλησης / Ενδοκοινοτικές Παραδόσεις

echo PaymentType::ON_CREDIT->label();
// Επί Πιστώσει

echo VatCategory::RATE_24->label();
// ΦΠΑ συντελεστής 24%

echo CountryCode::BE->label();
// Βέλγιο

var_dump(InvoiceType::labels());
var_dump(PaymentType::labels());
var_dump(VatCategory::labels());
var_dump(CountryCode::labels());
```

The labels are the official AADE descriptions, in Greek.

#### Enum helper methods

```php
use Firebed\AadeMyData\Enums\CountryCode;
use Firebed\AadeMyData\Enums\ExpenseClassificationType;
use Firebed\AadeMyData\Enums\InvoiceType;

$invoiceType = InvoiceType::TYPE_1_1;
$invoiceType->supportsFuelInvoice();
$invoiceType->hasCounterpart();
$invoiceType->supportsDeliveryNote();
$invoiceType->supportsSelfPricing();
$invoiceType->supportsTaxFree();

var_dump(CountryCode::europeanUnionCountries()); // all EU countries

CountryCode::BE->isInEuropeanUnion(); // true
CountryCode::US->isInEuropeanUnion(); // false

ExpenseClassificationType::VAT_361->isVatClassification(); // true

var_dump(ExpenseClassificationType::vatClassifications()); // all VAT classifications
```

#### New enums

- CountryCode
- CurrencyCode

#### Set model attributes in the constructor

```php
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Enums\RecType;
use Firebed\AadeMyData\Models\InvoiceDetails;

new InvoiceDetails([
  'lineNumber' => 1,
  'netValue' => 5,
  'recType' => RecType::FEES_LINE_WITH_VAT,
  'incomeClassification' => [
      [
          'classificationType' => IncomeClassificationType::E3_561_001,
          'classificationCategory' => IncomeClassificationCategory::CATEGORY_1_1,
          'amount' => '5'
      ]
  ]
]);
```

#### Chainable setters

`$invoice->setIssuer(...)->setCounterpart(...)`

#### New methods

- `Invoice::setTaxesTotals`
- `Invoice::setOtherTransportDetails` (removed in 6.0)

#### `add` methods that top up an amount

```php
$row->addNetValue(5);
$row->addVatAmount(1.2);
```

### Fixes

- Fixed the tax calculation in the invoice summary.
- Fixed `InvoiceDetails::setOtherMeasurementUnitQuantity`.
- Fixed `InvoiceDetails::setOtherMeasurementUnitTitle`.

## Upgrade guide from 3.x to 4.x

Version 4 is a full rewrite. Most changes are internal, but some change how you use the API.

### Update composer

```json
{
  "require": {
    "firebed/aade-mydata": "^4.0"
  }
}
```

### API changes

- `InvoicesDoc::addInvoice()` → `add()`
- `RequestedDoc::getInvoicesDoc()` → `getInvoices()`
- `RequestedDoc::getCancelledInvoicesDoc()` → `getCancelledInvoices()`
- `RequestedDoc::getIncomeClassificationsDoc()` → `getIncomeClassifications()`
- `RequestedDoc::getExpensesClassificationsDoc()` → `getExpensesClassifications()`
- `RequestedDoc::getPaymentMethodsDoc()` → `getPaymentMethods()`
- `put()` → `set()` on every model that extends `Firebed\AadeMyData\Models\Type` (`Model` since 6.0)
- Exception handling, see below

### Exceptions

Requests such as `SendInvoices` and `CancelInvoice` now throw `Firebed\AadeMyData\Exceptions\MyDataException`
or one of its subclasses. See [Exceptions](./exceptions).

```php
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\SendInvoices;

try {
    $send = new SendInvoices();
    $responses = $send->handle($invoices);
} catch (MyDataException $e) {
    echo $e->getMessage();
}
```

### Internal changes

These changes don't affect how you use the API.

#### Tests

Older versions had no tests. Version 4 has full test coverage.

#### DOMDocument

Older versions used `SimpleXMLElement`. It could not handle the growing myDATA XML well, especially
namespaced tags. Version 4 uses `DOMDocument`.

#### Invoice summary

Summarizing an invoice means computing the tax amounts, the net total, the gross total and the
classification totals, grouped by tax and classification type. In version 4 this is one method,
`summarizeInvoice()` on `Firebed\AadeMyData\Models\Invoice`:

```php
use Firebed\AadeMyData\Models\Invoice;

$invoice = new Invoice();
// ... set some details

$invoice->summarizeInvoice();
```

#### Automatic XML field order

myDATA's XML follows XSD schemas that set the order of the fields. Up to 3.x, the API wrote the fields in
the order you set them, which led to `XMLSyntaxError` responses.

Wrong order in 3.x and older:

```php
use Firebed\AadeMyData\Http\SendInvoices;
use Firebed\AadeMyData\Models\Counterpart;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\Issuer;

$invoice = new Invoice();

// Counterpart must come after Issuer
$invoice->setCounterpart(new Counterpart());

// Issuer must come before Counterpart
$invoice->setIssuer(new Issuer());

$send = new SendInvoices();
$send->handle($invoice);
```

The XSD puts `issuer` before `counterpart`, so myDATA answered:

```xml
<response>
    <index>1</index>
    <statusCode>XMLSyntaxError</statusCode>
    <errors>
        <error>
            <message>Line:13.Position:6.The element 'invoice' in namespace 'http://www.aade.gr/myDATA/invoice/v1.0' has invalid child element 'issuer' in namespace 'http://www.aade.gr/myDATA/invoice/v1.0'. List of possible elements expected: 'invoiceHeader' in namespace 'http://www.aade.gr/myDATA/invoice/v1.0'.</message>
            <code>101</code>
        </error>
    </errors>
</response>
```

> [!TIP]
> Since 4.x the library tracks the field order. Set the fields in any order, the XML is always in the right order.

#### Docs

The single `README.md` became a set of pages, one per part of the API, published at
[docs.invoicemaker.gr](https://docs.invoicemaker.gr).
