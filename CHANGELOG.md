# Changelog

All notable changes to this project are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/),
and this project adheres to [Semantic Versioning](https://semver.org/).

## [6.0.0] - Unreleased

### Upgrading

See the [upgrade guide](docs/upgrade-guide.md) and, for coding assistants, the
[AI upgrade guide](docs/upgrade-guide-ai.md), which lists every rename and behaviour change
with find/replace rules.

### Breaking

- Requires PHP 8.3. The library declares `strict_types` everywhere.
- Requires Guzzle 7.8 or newer (`guzzlehttp/guzzle: ^7.8 || ^8.0`).
- `Models\Type` is `Models\Model` and `Models\TypeArray` is `Models\ModelCollection`.
- The enum `Enums\PaymentMethod` is `Enums\PaymentType` (the `Models\PaymentMethod` model keeps its name).
- Enum cases have descriptive names (`PaymentType::CASH`, `VatCategory::RATE_24`, `TaxType::WITHHELD_TAX`, …) and the old code-like names (`METHOD_3`, `VAT_1`, …) are removed. `InvoiceType` keeps its names (`TYPE_1_1`, …). Backing values are unchanged; the appendix pages list the new name of every code and the AI upgrade guide has the full mapping.
- Model casts are declared by a `casts()` method instead of the `$casts` property, and list attributes with `Arr::of()` (`'invoiceDetails' => Arr::of(InvoiceDetails::class)`, `'correlatedInvoices' => Arr::of('int')`). The base model collects repeated XML elements itself, so every `set()` override is gone, and `ModelCollection` subclasses no longer take the child key in their constructor.
- An invalid enum value or a wrong type set on a cast attribute throws `InvalidAttributeException` instead of silently storing `null`. Readers parse myDATA XML in `Model::lenient()` mode, where unknown values still read as `null`.
- Scalar attributes are cast from their getter's return type, so values read from XML come back typed (`getIndex()` returns `1`, not `'1'`), in `toArray()` and `toJson()` too. An empty string means no value.
- `Invoice::getTaxesTotals()` returns `null` when no taxes totals exist. Empty wrapper elements (`<paymentMethods/>`, `<taxesTotals/>`, `<packingsDeclarations/>`) are no longer written.
- `ModelCollection::set()` with a single value appends it; a list replaces the items; `null` clears them.
- Removed: `TransportDetail` and `Invoice::{get,add,set}OtherTransportDetails()` (dropped by myDATA 2.0.1), `InvoiceSummary::{get,set}TotalInformationalTaxAmount()`, `WithheldPercentCategory::affectsTotalGrossValue()`, the `HasIterator` trait (`RequestedBookInfo` is no longer iterable; use `getBookInfo()`), the `Models\HasSchemaValidation` trait (use `Xml\Schema::validate($xml, 'InvoicesDoc')`), `GuzzleGateway::fail()` (override `GuzzleGateway::exception()`, which builds the exception every failure throws and every `beforeRetry()` listener sees), the protected `MyDataRequest::ensureProvider()` / `ensureERP()` (a request declares its channel with the `CHANNEL` constant, `Http\Channel::Erp` or `Provider`, checked before it is sent; `Connection::channel()` says which channel a connection is on).
- `MyDataRequest` keeps its static configuration API, but it now configures a `Connection` object (`MyDataRequest::defaultConnection()`); the private static properties are gone. The environment must be `dev` or `prod` (or the `Environment` enum).
- `XMLWriter::buildType()` is `buildModel()`, `XMLReader::createType()` is `createModel()`.
- The docs folder `docs/types` is `docs/models`.
- Different myDATA actions no longer extend each other: `SendInvoices` and `SendUnsignedInvoices` share the abstract `InvoicesDocRequest`, and `CancelInvoice`, `CancelDeliveryNote` and `CancelReceivingNote` the abstract `CancellationRequest`. `$request instanceof SendInvoices` is false for an unsigned send, and `instanceof CancelInvoice` false for a note cancellation; test the base class to cover both.
- `RequestDocs` and `RequestTransmittedDocs` are ERP-only and throw `UnsupportedChannelException` on a provider connection, where they sent the ERP parameters and read the wrong document. Providers use `RequestProviderDocs`.

### Added

- `Http\Connection`: credentials, environment, channel, Guzzle options, gateway and retry policy as an immutable object with a cached Guzzle client. `MyDataRequest::using($connection)` carries one request over it; `MyDataRequest::defaultConnection()` / `setDefaultConnection()` expose the default.
- `MyData` client bound to a connection, with a method per myDATA call (`sendInvoices()`, `requestDocs()`, `cancelInvoice()`, …), `request()` to bind any request and `lastRequest()` for its XML. Built for multi-tenant code.
- Retries with exponential backoff on 429, 502, 503, 504 and connection failures, honouring `Retry-After`: `Connection::withRetries()` and `MyDataRequest::setRetries()`. Timeouts are never retried.
- Rate-limit awareness: `RateLimitExceededException::getRetryAfter()` / `getRetryAt()` read myDATA's `Retry-After` header or its "Try again in N seconds" message; a 429 is retried only when the wait is at most 10 seconds, otherwise it is thrown at once instead of hammering a throttled key. `Connection::withThrottle()` / `MyDataRequest::setThrottle()` space the requests of a connection, and `Connection::withRateLimitWait()` / `MyDataRequest::setRateLimitWait()` sit out the wait and resend; sitting out a rate limit spends neither a retry nor the backoff of `withRetries()`. The README documents the limits measured on the sandbox.
- Pagination generators that follow the continuation token: `pages()` on `RequestDocs`, `RequestTransmittedDocs`, `RequestMyIncome`, `RequestMyExpenses`, `RequestVatInfo` and `RequestE3Info`, and `invoices()` on the document requests, all resumable from a continuation token (`nextPartitionKey`, `nextRowKey`).
- `Http\Environment` enum.
- `Model::fromArray()`, `toJson()`, `JsonSerializable`, `unset()`, `cast()`, `Model::lenient()`; `ModelCollection::last()`, `map()`, `filter()`, `childKey()` and `$collection[] = $item`.
- `Casts\Arr`, `Casts\Cast`, `Exceptions\InvalidAttributeException`.
- PHPStan (level 7) and Laravel Pint, run in CI with `composer analyse` and `composer lint`.
- `MyData::fake()` for application tests: a `Testing\FakeGateway` answers every request, whatever its connection, with the given responses (an XML string, a model, a list of responses or invoices, an exception, a closure or a `FakeGateway::sequence()`) or with defaults (sent documents and cancellations succeed with sequential marks and the invoice uid, searches find nothing), and records them for `assertSent()`, `assertNotSent()`, `assertSentCount()` and `assertNothingSent()`. `FakeGateway::accepted()` / `rejected()` build responses; `MyData::stopFaking()` ends it. Built on `MyDataRequest::intercept()`.
- Validation before sending: `Connection::withValidation()` / `MyDataRequest::setValidation()` check the XML of every document request against its myDATA XSD and throw `SchemaValidationException` (with `getErrors()`) instead of sending an invalid document. Off by default.
- Listeners and logging: `Connection::afterSending()` receives an `Http\Exchange` per request (request and response XML or the exception, duration), `Connection::beforeRetry()` an `Http\Retry` per retry, and `Connection::withLogger()` writes both to a PSR-3 logger without credentials; unless `withBodies: true` it also leaves out the XML and the query parameters that identify someone, redacts failure messages (URL queries, VAT numbers) and names the exception by class and code instead of attaching it; a second `withLogger()` replaces the first, `null` removes it. A listener that throws is skipped, so it never hides myDATA's answer or the failure. A listener given a `key` replaces the one with the same key; `Connection::withoutListener($keyOrListener)` and `withoutListeners()` remove them. Static: `MyDataRequest::afterSending()`, `beforeRetry()`, `setLogger()`, `removeListener()`, `removeListeners()`.
- `MyDataRequest::action()` (the myDATA action, e.g. `'SendInvoices'`), `Xml\Schema` (XSD validation), `Xml\ModelWriter` (any model as XML).
- Dependency: `psr/log` (`^2.0 || ^3.0`).
- Provider channel: `RequestProviderDocs` (myDATA's provider `RequestTransmittedDocs`, by `issuervat` and `mark`) answers a `RequestedProviderDoc` of `ProviderInvoice` (issuer VAT, mark, uid, authentication code) with `pages()`, `invoices()` and `findByUid()`; `RequestReceiverInfo` answers a `ReceiverInfoDoc` (reception providers and emails); `SendUnsignedInvoices` transmits invoices under issue whose Provider's Signature was never matched with an issued invoice, marked "Υπό Έκδοση" as Α.1155/2023 requires (myDATA answers a mark but no authentication code). `MyData::requestProviderDocs()`, `requestReceiverInfo()`, `sendUnsignedInvoices()`.
- `Response::getDuplicate()` reads error 228 ("It has already been sent for another invoice") into a `Duplicate` (uid, mark, and the authentication code once myDATA has stored the invoice), and `Duplicate::toResponse()` builds the successful response it stands for. `Response::getReceptionProviders()` reads the providers myDATA names in a provider response.

### Changed

- Guzzle 8 is supported next to Guzzle 7, and both are tested in CI. On Guzzle 8 a timeout before or after the response headers is a `MyDataTimeoutException` (never retried, myDATA may have processed the request) and a connect timeout, where the request never left, is a `MyDataConnectionException` (retried like any other connection failure). On Guzzle 7 every cURL timeout (error 28) is a `MyDataTimeoutException`; 5.x only recognised the ones reported as `RequestException`, so a timeout reported as `ConnectException` could be retried. Guzzle 7 support will be dropped in the next major.
- The Guzzle client is created once per connection instead of once per request.
- Casts, child keys and getter types are resolved once per model class and cached.
- `InvoiceHeader::setAa()` accepts `string|int`; `CancelInvoice`, `CancelDeliveryNote` and `CancelReceivingNote` accept `string|int` marks and VAT numbers.
- `TaxTotals::getTaxCategory()` returns `?int`, the category code; `setTaxCategory()` still accepts the category enums.
- `HasLabels::labels()` returns the labels keyed by case value.
- Request XML is no longer pretty-printed by default; `getRequestXml()` and `getResponseXML()` format on demand.
- `Connection::make()` and `MyData::connect()` default to the development environment.
- `ModelCollection::push()` appends one item; `set()` with a list replaces the items.
- Enums that can be resolved from a case name implement `Enums\TryFromName` (`DeliveryStatus`), which the casts fall back to.
- Dev dependencies: PHPUnit 11/12, symfony/var-dumper 7. CI tests PHP 8.3, 8.4 and 8.5.
- The XML writers create every element in the document's default namespace, so `getRequestDom()` validates against the XSD as it is; the serialized XML is unchanged.
- `validate()` on the models reports the whole libxml message of an error it cannot split into field and message, instead of `null`, and restores the libxml error handling it found.

## [5.14.2] - 2026-11-01

### Fixed

- An invoice's packages are written as `<packingsDeclarations><Packages>…</Packages></packingsDeclarations>`, as the XSD requires (new `PackingsDeclaration` model). Before, each `PackagingDetail` became its own `packingsDeclarations` element, which myDATA rejects (error 101). All packages go into one `packingsDeclarations`, since myDATA accepts only one per invoice.
- Reading an invoice keeps its packages: `getPackingsDeclarations()` returns them as `PackagingDetail[]` instead of throwing a `TypeError`, and writing the invoice again no longer produces an empty `<packingsDeclarations/>`.
- `setPackingsDeclarations([])` removes the packages instead of leaving an element that fails XSD validation.
- Building a model from attributes accepts a plain list of objects for a list type (e.g. `new Invoice(['paymentMethods' => [$a, $b]])`). Before, only the first item was passed on, which failed with a `TypeError`.

## [5.14.1] - 2026-10-31

### Changed

- `SendIncomeClassification` and `SendExpensesClassification` extend `MyDataXmlRequest`, like `SendInvoices`. `MyDataXmlRequest::request()` takes an optional `$query` argument.

### Fixed

- Parsing income and expenses classifications (e.g. from `RequestDocs` or `RequestTransmittedDocs`) keeps every `invoicesIncomeClassificationDetails`, `incomeClassificationDetailData`, `invoicesExpensesClassificationDetails` and `expensesClassificationDetailData` element. A single element no longer makes the getters fail with a `TypeError`, and repeated elements no longer overwrite each other.
- `SendIncomeClassification` and `SendExpensesClassification` keep the request in `getRequestDom()` and set `getResponseDom()`. Before, the response replaced the request and `getResponseDom()` stayed `null`.
- Iterating an empty `TypeArray` document (e.g. a `ResponseDoc` with no responses) no longer fails, and `first()` returns `null` on an empty document.
- `ExpensesClassificationsDoc` returns `null` for a missing item (e.g. `$doc[0]` on an empty document) instead of throwing a `TypeError`, like the other documents.
- On PHP 8.1 and 8.2, the `MyDataAuthenticationException` message of a 401 whose body is not valid UTF-8 is valid UTF-8, with invalid bytes replaced by `?` as on PHP 8.3 and later.
- `IncomeClassificationsDoc` and `ExpensesClassificationsDoc` declare `InvoiceIncomeClassification` and `InvoiceExpensesClassification` as their items, so static analysis knows about `getInvoiceMark()` and `getClassificationMark()`.

## [5.14.0] - 2026-10-18

### Upgrading

Requests that used to return an empty result when myDATA answered with an error now throw `ErrorResponseException`, which is a `TransmissionFailedException` (and so a `MyDataException`). Code that already catches `MyDataException` needs no change. Code that relied on the empty result, or that only catches `MyDataAuthenticationException`, should catch `ErrorResponseException` and read `getErrors()`.

### Added

- `ErrorResponseException` (a `TransmissionFailedException`), thrown when myDATA answers with an error document (`ResponseDoc`) instead of the expected response. Its message lists each `code: message` of every response and `getErrors()` returns the errors. Its code is the HTTP status for an HTTP error, otherwise the first error's code.

### Changed

- Readers of a document other than `ResponseDoc` throw `ErrorResponseException` when given an error document, so the requests that use them (`RequestDocs`, `RequestTransmittedDocs`, `RequestMyIncome`, `RequestMyExpenses`, `RequestVatInfo`, `RequestE3Info`, `RequestStatements`, `RequestDeliveryNoteStatus`, `RequestGroupQrDetails`, `GenerateGroupQrCode`) no longer return an empty result with the error code lost. `getResponseXML()` still returns the error document.
- An HTTP error whose body is an error document (e.g. HTTP 400) throws `ErrorResponseException` with a readable message instead of a `TransmissionFailedException` carrying the raw XML. The exception code is still the HTTP status.
- A 401 now carries the reason myDATA gives (e.g. the user is not authorized to view a note's status) as the `MyDataAuthenticationException` message: the JSON `message` when there is one, otherwise the body, capped at 500 characters. The generic "check your user id and subscription key" message is used when there is no usable reason (empty body or an HTML page).
- `RequestDeliveryNoteStatus::handleUsingQrUrl()` ignores its `issuerVatNumber` parameter, now deprecated. myDATA accepts it only with `mark` (§3.2.4) and answers HTTP 400 when it comes with `qrUrl`.

### Fixed

- `RequestDeliveryNoteStatus::handleUsingQrUrl()` no longer encodes the already percent-encoded QR URL a second time, which made every lookup fail with 807 Invalid QR.
- `RequestDeliveryNoteStatus::handleUsingQrUrl()` lookups with an `issuerVatNumber` no longer fail with HTTP 400 (see Changed).
- `DeliveryStatus::tryFromName()` also reads the specification's spelling of the status names (`Registered`, `InTransit`, `InTransit (Return)`, …) next to the `REGISTERED`-style names myDATA dev returns.
- `getResponseXML()` and `getRequestXml()` return `null` before a request has been made instead of failing with a fatal error.

## [5.13.0] - 2026-10-06

### Added

- `squashInvoiceRows()` option `vatAmountTolerance`. When set, rows with the same categories are squashed into as many rows as needed to keep each row's vat amount within the tolerance of its net value times its vat rate, instead of a single row. myDATA rejects a row more than 1.00 away (error 229), which many rows with tiny rounding differences reach once summed. Defaults to `null`, which keeps the single-row behaviour.
- `SquashInvoiceRows::groups()` returns the rows that are squashed into each row, without summing them, so callers can validate a squashed row and map it back to its original rows. `handle()` sums these groups, so both always agree.

### Fixed

- Squashing no longer renumbers the caller's original rows with a `recType`, so `unSquashInvoiceRows()` restores their line numbers.

## [5.12.0] - 2026-09-12

Support for the myDATA API **v2.0.2** (AADE official ERP specification, September 2026).
The release focuses on the Digital Delivery Note and the new Receiving Note flow
(Δελτίο Ποσοτικής Παραλαβής, invoice types 10.1 / 10.2). All new fields are
optional and backwards compatible.

### Added

- `ConfirmDeliveryReturn` ERP request (`Http\DigitalGoodsMovement\ConfirmDeliveryReturn`) with its `DeliveryReturn` request model and `DeliveryReturnWriter`. Called by the issuer to declare completion of a movement on return; the response carries `deliveryReturnMark`.
- `CancelReceivingNote` provider request (`Http\CancelReceivingNote`) to cancel a Receiving Note (types 10.1 / 10.2) by `mark` + `entityVatNumber`.
- `ReceivingNotePurpose` enum (codes 1–7) for the receiving-note issue reason.
- `InvoiceHeader` fields: `receivingNotePurpose`, `otherReceivingNotePurposeTitle`, `nonObligatedRecipient`, `withoutDigitalTransportTracking`.
- `TransportDetails::packingsDeclaration` — an unbounded list of `PackagingDetail` for carrier packaging declarations.
- `Response::getDeliveryReturnMark()` for the digital-goods-movement response.
- `DeliveryStatus::IN_TRANSIT_RETURN` (9) and `DeliveryEventType::CONFIRM_RETURN` / `REGISTER_TRANSFER_RETURN`.
- `RequestDeliveryNoteStatus::handleUsingQrUrl()` to look up a delivery note status by `qrUrl` as an alternative to `mark`.
- `FuelCode` — fuel codes that were in the XSD but missing from the enum: `14`, `15` and `33`–`38`. The enum now covers all 29 codes from myDATA section 8.17 (previously such values were dropped when reading invoices).
- `ExpenseClassificationType` — codes `E3_881_001`–`E3_881_004` (Πωλήσεις για λογαριασμό Τρίτων), previously missing from the enum.

### Changed

- `InvoiceType::supportsDeliveryNote()` now also allows types `1.4`, `3.1`, `3.2` and `11.5`.
- Bundled XSD schemas updated to the v2.0.2 set (incl. the new `ConfirmDeliveryReturn-v2.0.2.xsd`).

### Fixed

- `InvoiceHeader` `$casts` key for `reverseDeliveryNotePurpose` was mis-cased (`ReverseDeliveryNotePurpose`), so an integer value was never cast to the enum.
- `FuelCode` label typos, verified against the v2.0.2 documentation: `Diesel Heatnn` → `Diesel Heating` (30), `Diesel Heat premium` → `Diesel Heating premium` (31), `Diesel Linht` → `Diesel Light` (32).
- Documentation example corrections (README delivery-note quick example, `request-docs`/`request-transmitted-docs` continuation examples, `ecr-token` example, date format) and several `@return` PHPDoc types on `Transport`.

## [5.11.0] - 2026-09-09

A `Gateway` seam so a request can be routed through a licensed e-invoicing
provider instead of going straight to AADE.

### Added

- `Http\Gateway` seam: a `Gateway` carries a request's XML to its destination. `GuzzleGateway` (the default) talks to AADE as before; `MyDataRequest::setGateway()` installs another one globally and `usingGateway()` per request, so a request can be routed through an e-invoicing provider without changing the code that builds and sends invoices. `SendInvoices::getInvoicesDoc()` and `SendPaymentsMethod::getPaymentMethodsDoc()` expose the models to a gateway.
- Attributes a provider gateway reads that are never written to the myDATA XML (absent from `$expectedOrder`, kept by `toArray()` / `make()`): `InvoiceHeader::setIssueTime()`, `InvoiceDetails::setUnitPrice()`, `InvoiceDetails::setDiscount(DiscountType, float)` with the `DiscountType` enum (1 percentage, 2 amount), and free key/value extra fields (`setExtraFields()`, `addExtraField()`) on `Invoice`, `InvoiceDetails` and `PaymentMethodDetail`.

### Changed

- `MyDataRequest::handleTransmissionException()` (`protected`) moved to `GuzzleGateway`: requests no longer map Guzzle exceptions themselves, the gateway that carried the request does. Subclasses that overrode it should move that logic into a `Gateway` (see `docs/upgrade-guide.md`).

## [5.10.0] - 2026-03-12

myDATA API **v2.0.1** support, including Digital Goods Movement (Ψηφιακή Διακίνηση Αγαθών) for the ERP channel.

### Added

- Digital Goods Movement — six HTTP methods: `RegisterTransfer`, `ConfirmDeliveryOutcome`, `RejectDeliveryNote` (by MARK or QR URL), `RequestDeliveryNoteStatus`, `GenerateGroupQrCode` and `RequestGroupQrDetails`.
- `ProvidersSignature` now supports the `EndToEndReferenceID` field and its XML serialization.

### Changed

- Upgraded all XSD schema files to v2.0.1.
- `Invoice` model now uses a version constant for schema validation.
- PHP 8.4 compatibility — fixed implicitly nullable parameter declarations across 25+ files.

### Removed

- Deprecated `TransportDetail` factory.

### Fixed

- `Invoice::setPackingsDeclarations()` ignored its parameter and always set `null`.
- `Invoice::setPaymentMethods()` used a loose `==` null check — changed to strict `===`.
- `MyDataRequest` — added a null-safe operator on `getResponse()?->getBody()`.
- `Type` no longer pushes `null` values.

## [5.9.0] - 2025-12-30

### Added

- `LiableUserCategory` enum.

### Changed

- Restructured the `ProviderType` and `RecallStatus` namespaces.

## [5.8.1] - 2025-12-15

Patch release.

## [5.8.0] - 2025-12-10

### Added

- Statements API — `SendStatement`, `RecallStatement` and `RequestStatements` methods.

## [5.7.0] - 2025-11-13

### Changed

- Implemented myDATA **v1.0.12** changes.
- Updated documentation links and versions to myDATA v1.0.12.

## [5.6.4] - 2025-08-25

### Fixed

- Incorrect classification amount after squashing invoice rows.

## [5.6.3] - 2025-08-22

### Changed

- Renamed "Stamp Duty" (Χαρτόσημο) references to "Digital Transaction Fee" (Ψηφιακό Τέλος συναλλαγής).

## [5.6.2] - 2025-08-07

### Fixed

- Expense and income classification categories now return the correct types.
- `EnumCacheTest` made compatible with PHP 8.3.

## [5.6.1] - 2025-08-07

### Fixed

- Method return types in the `InvoiceType` enum.

## [5.6.0] - 2025-07-02

myDATA API **v1.0.11** support.

### Added

- `qrUrl` returned on invoices from electronic-invoicing providers (usable to build a URL-type QR code pointing to an AADE page).
- New move purpose `20 - Μεταφορές – Ταχυμεταφορές`.
- New special invoice category `13 - Δυσχέρεια Συσχέτισης για προσυμπλήρωση Φ2 και Ε3`.
- New `transmissionFailure` value for providers (t + 2).
- New provider method `CancelDeliveryNote` for cancelling delivery documents.
- Digital Transaction Fee can be transmitted on payroll (17.1); types 11.5 and the FUEL format can be transmitted from ΦΗΜ.

### Changed

- Move purposes renamed: `7 - Επεξεργασία - Συναρμολόγηση - Αποσυναρμολόγηση`, `8 - Ενδοδιακίνηση`.
- Move purposes `6, 15, 16, 17, 18` can no longer be transmitted.
- "Stamp Duty" converted to "Digital Transaction Fee" (naming only).

## [5.5.4] - 2025-04-07

### Added

- `Invoice::unSquashInvoiceRows()` to restore an invoice to its original state.
- `Invoice::isSquashed()` to check whether an invoice is squashed.
- `InvoicesDocReader` to convert a transmitted `InvoicesDoc` XML invoice back into an invoice model.

## [5.5.3] - 2025-04-02

### Fixed

- Support invoice detail type in the squashing logic.

## [5.5.2] - 2025-03-12

### Fixed

- Added missing codes in the classifications config file.

## [5.5.0] - 2025-03-09

### Added

- Unit-of-measure support check per invoice type.
- Tax classification validation for tax types and categories.

## [5.4.1] - 2025-02-11

### Added

- Timeout handling for myDATA requests.

## [5.4.0] - 2025-01-26

### Added

- Support for sending income and expense classifications to myDATA.

## [5.3.0] - 2025-01-10

### Fixed

- Gross value calculation now ignores informational taxes.

## [5.2.5] - 2025-01-03

myDATA API **v1.0.10** support.

### Added

- New method for E3 record retrieval (by invoice or day).
- Fuel invoice transmission via the ERP channel (`fuelInvoice = true`).
- Dispatch note transmission (9.3) via ERP and providers (FUEL format).
- New XSD files (v1.0.10).

### Changed

- Allow a past `dispatchDate` with `transmissionFailure`.

### Removed

- Active business check for wholesale.

## [5.2.4] - 2024-11-19

### Added

- Handle empty `receptionEmails` in `Response` (sets a new `ReceptionEmails` object).

### Fixed

- `TypeArray::count()` now defaults to an empty array to avoid a potential issue.

## [5.2.2] - 2024-10-20

### Changed

- Added comments and fixed code in `HasIterator` and `TypeClassification`.

## [5.2.1] - 2024-10-16

### Changed

- `InvoicesDocWriter` document version set to v1.0.9.
- Better environment detection.

### Fixed

- Handle special characters in XML generation.

## [5.2.0] - 2024-10-12

### Changed

- Renamed `InvoiceSummary::setTotalInformationTaxAmount` to `setTotalInformationalTaxAmount` and the `$totalInformationalTaxAmount` property for consistency (possible low-probability breaking change).
- Changed property visibilities to `protected`.

## [5.1.4] - 2024-10-12

### Added

- Enum type-checking cache (with `enumCache` test coverage).
- `InvalidResponseException` thrown when the myDATA API response is empty.

### Fixed

- Validate `expensesClassification` and `incomeClassification` in the `set` method.
- Round total gross values inside the getter for consistency.

## [5.1.0] - 2024-08-27

### Fixed

- `RequestVatInfo::handle` — `$groupedPerDay` defaults to `false` for backwards compatibility.

## [5.0.0] - 2024-08-21

Major release.

### Added

- Squashing invoice rows (`Invoice::squashInvoiceRows()`).
- Invoice XML validation against XSD files (`Invoice::validate()`).
- Invoice XML preview (`Invoice::toXml()`).
- Model constructors that populate attributes from mixed array values.
- Fluent (chainable) model setters.

## [4.0.7] - 2024-06-07

### Fixed

- `setOtherMeasurementUnitQuantity` and `setOtherMeasurementUnitTitle`.

## [4.0.6] - 2024-02-29

### Fixed

- Null values are now removed from the generated XML.

## [4.0.5] - 2024-02-28

### Added

- Services for verifying the validity of a VAT number (TaxisNet and Vies registry lookup).

## [4.0.0] - 2024-02-25

Major release. See the upgrade guide for migrating from 3.x to 4.x.

## [3.1.6] - 2024-01-18

### Fixed

- Ensure simple values do not throw an exception.

## [3.1.5] - 2023-12-20

Patch release.

## [3.1.4] - 2023-02-01

### Added

- Ability to change the `GuzzleHttp\Client` verification (works around cURL error 60: SSL certificate problem).

## [3.1.3] - 2023-01-31

### Changed

- New developer test API key URL.

## [3.1.2] - 2023-01-18

Patch release.

## [3.1.1] - 2023-01-16

### Changed

- Updated the development URLs and the developer portal URL to match the official ones.

## [3.1.0] - 2022-10-08

Maintenance release.

## [3.0.2] - 2022-10-01

Patch release.

## [3.0.1] - 2022-10-01

Patch release.

## [3.0.0] - 2022-10-01

Major release (no published release notes).

## [2.0.0] - 2022-03-07

### Changed

- Migrated to PHP 8.1.
- Synchronized with myDATA v1.0.5.
- Introduced PHP enum cases instead of class constants.
- Better XML parsing.

## [1.0.1] - 2022-03-05

### Fixed

- Variable naming.

## [1.0.0] - 2022-01-28

Initial release.

[Unreleased]: https://github.com/firebed/aade-mydata/compare/v5.14.2...HEAD
[5.14.2]: https://github.com/firebed/aade-mydata/compare/v5.14.1...v5.14.2
[5.14.1]: https://github.com/firebed/aade-mydata/compare/v5.14.0...v5.14.1
[5.14.0]: https://github.com/firebed/aade-mydata/compare/v5.13.0...v5.14.0
[5.13.0]: https://github.com/firebed/aade-mydata/compare/v5.12.0...v5.13.0
[5.12.0]: https://github.com/firebed/aade-mydata/compare/v5.11.0...v5.12.0
[5.11.0]: https://github.com/firebed/aade-mydata/compare/v5.10.4...v5.11.0
[5.10.0]: https://github.com/firebed/aade-mydata/compare/v5.9.0...v5.10.0
[5.9.0]: https://github.com/firebed/aade-mydata/compare/v5.8.1...v5.9.0
[5.8.1]: https://github.com/firebed/aade-mydata/compare/v5.8.0...v5.8.1
[5.8.0]: https://github.com/firebed/aade-mydata/compare/v5.7.0...v5.8.0
[5.7.0]: https://github.com/firebed/aade-mydata/compare/v5.6.4...v5.7.0
[5.6.4]: https://github.com/firebed/aade-mydata/compare/v5.6.3...v5.6.4
[5.6.3]: https://github.com/firebed/aade-mydata/compare/v5.6.2...v5.6.3
[5.6.2]: https://github.com/firebed/aade-mydata/compare/v5.6.1...v5.6.2
[5.6.1]: https://github.com/firebed/aade-mydata/compare/v5.6.0...v5.6.1
[5.6.0]: https://github.com/firebed/aade-mydata/compare/v5.5.4...v5.6.0
[5.5.4]: https://github.com/firebed/aade-mydata/compare/v5.5.3...v5.5.4
[5.5.3]: https://github.com/firebed/aade-mydata/compare/v5.5.2...v5.5.3
[5.5.2]: https://github.com/firebed/aade-mydata/compare/v5.5.0...v5.5.2
[5.5.0]: https://github.com/firebed/aade-mydata/compare/v5.4.1...v5.5.0
[5.4.1]: https://github.com/firebed/aade-mydata/compare/v5.4.0...v5.4.1
[5.4.0]: https://github.com/firebed/aade-mydata/compare/v5.3.0...v5.4.0
[5.3.0]: https://github.com/firebed/aade-mydata/compare/v5.2.5...v5.3.0
[5.2.5]: https://github.com/firebed/aade-mydata/compare/v5.2.4...v5.2.5
[5.2.4]: https://github.com/firebed/aade-mydata/compare/v5.2.2...v5.2.4
[5.2.2]: https://github.com/firebed/aade-mydata/compare/v5.2.1...v5.2.2
[5.2.1]: https://github.com/firebed/aade-mydata/compare/v5.2.0...v5.2.1
[5.2.0]: https://github.com/firebed/aade-mydata/compare/v5.1.4...v5.2.0
[5.1.4]: https://github.com/firebed/aade-mydata/compare/v5.1.0...v5.1.4
[5.1.0]: https://github.com/firebed/aade-mydata/compare/v5.0.0...v5.1.0
[5.0.0]: https://github.com/firebed/aade-mydata/compare/v4.0.7...v5.0.0
[4.0.7]: https://github.com/firebed/aade-mydata/compare/v4.0.6...v4.0.7
[4.0.6]: https://github.com/firebed/aade-mydata/compare/v4.0.5...v4.0.6
[4.0.5]: https://github.com/firebed/aade-mydata/compare/v4.0.0...v4.0.5
[4.0.0]: https://github.com/firebed/aade-mydata/compare/v3.1.6...v4.0.0
[3.1.6]: https://github.com/firebed/aade-mydata/compare/v3.1.5...v3.1.6
[3.1.5]: https://github.com/firebed/aade-mydata/compare/v3.1.4...v3.1.5
[3.1.4]: https://github.com/firebed/aade-mydata/compare/v3.1.3...v3.1.4
[3.1.3]: https://github.com/firebed/aade-mydata/compare/v3.1.2...v3.1.3
[3.1.2]: https://github.com/firebed/aade-mydata/compare/v3.1.1...v3.1.2
[3.1.1]: https://github.com/firebed/aade-mydata/compare/v3.1.0...v3.1.1
[3.1.0]: https://github.com/firebed/aade-mydata/compare/v3.0.2...v3.1.0
[3.0.2]: https://github.com/firebed/aade-mydata/compare/v3.0.1...v3.0.2
[3.0.1]: https://github.com/firebed/aade-mydata/compare/v3.0.0...v3.0.1
[3.0.0]: https://github.com/firebed/aade-mydata/compare/v2.0.0...v3.0.0
[2.0.0]: https://github.com/firebed/aade-mydata/compare/v1.0.1...v2.0.0
[1.0.1]: https://github.com/firebed/aade-mydata/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/firebed/aade-mydata/releases/tag/v1.0.0
