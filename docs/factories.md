# Factories

Models such as `Invoice` have a factory that fills them with random data. Use it in tests, often
together with [`MyData::fake()`](./testing).

Factories need Faker. Add it to your dev dependencies:

```shell
composer require --dev fakerphp/faker
```

```php
use Firebed\AadeMyData\Http\SendInvoices;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\MyData;

$fake = MyData::fake();

$invoice = Invoice::factory()->make();
(new SendInvoices())->handle($invoice);

$fake->assertSentCount(1, SendInvoices::class);
```

The invoice passes the myDATA XSD, but its values are random (invoice type, VAT numbers, amounts).
It is not a real invoice. Don't send it to myDATA.

## Usage

```php
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Models\InvoiceDetails;
use Firebed\AadeMyData\Models\InvoiceHeader;
use Firebed\AadeMyData\Models\Response;

// Several models: returns a list
$invoices = Invoice::factory(3)->make();

// Override attributes. A value can be a factory too.
$invoice = Invoice::factory()->make([
    'invoiceHeader' => InvoiceHeader::factory()->state(['invoiceType' => InvoiceType::TYPE_1_1]),
]);

$rows = InvoiceDetails::factory(2)->make(['netValue' => 10]);

// Leave attributes out
$invoice = Invoice::factory()->except(['counterpart', 'taxesTotals'])->make();

// A cancellation response
$response = Response::factory()->cancelled()->make();
```

| Method | Does |
|---|---|
| `Model::factory($count = 1)` | Returns the model's factory |
| `make($attributes = [])` | Builds the model, or a list when `$count` is not 1 |
| `state($attributes)` | Attributes that win over `make()` attributes |
| `except($fields)` | Attributes to leave out |

## Models with a factory

`Address`, `EntityType`, `ExpensesClassification`, `IncomeClassification`, `Invoice`, `InvoiceDetails`,
`InvoiceHeader`, `InvoiceSummary`, `Issuer`, `Counterpart`, `OtherDeliveryNoteHeader`, `Party`,
`PaymentMethod`, `PaymentMethodDetail`, `PaymentMethods`, `Response`, `Ship`, `TaxTotals`, `TaxesTotals`,
`Statements\Statement`, `Statements\StatementResponse`.
