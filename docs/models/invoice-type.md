# Invoice

`Invoice` is one invoice: the one you send with `SendInvoices` and the one you read back with `RequestDocs` or
`RequestTransmittedDocs`.

```php
use Firebed\AadeMyData\Models\Invoice;

$invoice = new Invoice();
$invoice->setIssuer($issuer);
$invoice->setCounterpart($counterpart);
$invoice->setInvoiceHeader($header);
$invoice->addPaymentMethod($payment);
$invoice->addInvoiceDetails($row);
$invoice->summarizeInvoice();
```

See the [invoice examples](../invoices/examples) for complete invoices.

| Field                 | Type                                                     | Required | Description |
|-----------------------|----------------------------------------------------------|----------|-------------|
| uid                   | `string`                                                 | No       | Invoice id. Set by myDATA |
| mark                  | `string`                                                 | No       | MARK of the invoice. Set by myDATA |
| cancelledByMark       | `string`                                                 | No       | MARK of the cancellation. Set by myDATA, only on cancelled invoices |
| authenticationCode    | `string`                                                 | No       | Authentication code. Set by myDATA, provider channel only |
| transmissionFailure   | `TransmissionFailure` or `int`                           | No       | Connection failure code, see below. The getter returns `int` |
| issuer                | [Issuer](party-type)                                     | No       | Issuer |
| counterpart           | [Counterpart](party-type)                                | No       | Recipient |
| invoiceHeader         | [InvoiceHeader](invoice-header-type)                     | Yes      | Header |
| paymentMethods        | [`PaymentMethodDetail[]`](payment-method-detail-type)    | No       | Payments. Add one with `addPaymentMethod()`. The getter returns a `PaymentMethods` collection |
| invoiceDetails        | [`InvoiceDetails[]`](invoice-row-type)                   | Yes      | Rows. Add one with `addInvoiceDetails()` |
| taxesTotals           | [`TaxTotals[]`](tax-totals-type)                         | No       | Invoice-level taxes. Add one with `addTaxesTotals()`. The getter returns a `TaxesTotals` collection or `null` |
| invoiceSummary        | [InvoiceSummary](invoice-summary-type)                   | Yes      | Totals |
| qrCodeUrl             | `string`                                                 | No       | URL for the invoice QR code. Set by myDATA |
| downloadingInvoiceUrl | `string`                                                 | No       | URL where the recipient downloads the invoice. Provider channel only |
| packingsDeclarations  | `PackagingDetail[]`                                      | No       | Packaging declarations. Delivery notes only |
| invoiceDeliveryStatus | `DeliveryStatus`                                         | No       | Status of the delivery note. Read only, set by myDATA |
| deliveryLifecycle     | `DeliveryLifecycle`                                      | No       | Events of the delivery note. Read only, set by myDATA |

`extraFields` (`setExtraFields(array)`, `addExtraField($key, $value)`) is not a myDATA field and never goes into the
XML. Gateways that send through an e-invoicing provider forward it as the provider's extra fields.

## Helpers

| Method                          | Description |
|---------------------------------|-------------|
| `summarizeInvoice()`            | Builds the [InvoiceSummary](invoice-summary-type) from the rows and taxes |
| `squashInvoiceRows()`           | Merges similar rows, see [Squashing invoice rows](../squashing-invoice-rows) |
| `getTotalPaymentAmount()`       | Sum of the payment amounts |
| `guessUid()`                    | Computes the uid from the issuer and header. It leaves out the recipient VAT number, so it does not match for categories B1 and B2 |
| `validate()`                    | Checks the invoice against the myDATA XSD. Returns a list of errors, empty when valid |
| `toXml()`                       | The invoice as XML. `toXml(true)` wraps it in `InvoicesDoc` |

## Notes

- myDATA computes `uid` as the SHA-1 hash of: issuer VAT number, issue date, branch, invoice type, series,
  number and variation type (when present). For categories B1 and B2 (domestic or foreign recipient) the
  recipient VAT number is also part of it. The hash uses ISO-8859-7 encoding.
- myDATA computes `authenticationCode` as the SHA-1 hash of: issuer VAT number, issue date, branch, invoice type,
  series, number, MARK, total gross value, total VAT and recipient VAT number.
- `qrCodeUrl` points to an AADE page that shows the invoice. Print it as a QR code.
- `taxesTotals` holds taxes of all kinds except VAT that apply to the whole invoice. When you use it, you cannot
  put taxes other than VAT on the rows.
- `transmissionFailure` values (`Firebed\AadeMyData\Enums\TransmissionFailure`):

| Value | Case                                  | When |
|-------|---------------------------------------|------|
| 1     | `ERP_CONNECTION_FAILURE`              | The entity could not reach the provider (provider channel) |
| 2     | `MYDATA_CONNECTION_FAILURE`           | The provider could not reach myDATA (provider channel) |
| 3     | `CONNECTION_LOSS`                     | Connection loss (ERP only) |
| 4     | `UTILITY_SUPPLIER_CONNECTION_FAILURE` | Invoices of entities in art. 5 par. 2 (c) of law 1138/2020, e.g. electricity and gas suppliers, water company |

## Digital Goods Movement fields

`packingsDeclarations` declares the packages of a delivery note. myDATA accepts one declaration per invoice, so the
library writes all packages in a single `packingsDeclarations` element.

```php
use Firebed\AadeMyData\Enums\DigitalGoodsMovement\PackagingType;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\PackagingDetail;

$invoice->addPackagingDeclaration(new PackagingDetail(PackagingType::PALLET, 10));
$invoice->addPackagingDeclaration(new PackagingDetail(PackagingType::BOX, 50));

// or all at once
$invoice->setPackingsDeclarations([
    new PackagingDetail(PackagingType::CRATE, 5),
    new PackagingDetail(PackagingType::BARREL, 2),
]);
```

myDATA rejects a quantity of 0 or less. It also rejects `PackagingType::OTHER` without `otherPackagingTypeTitle`,
and an `otherPackagingTypeTitle` with any other type. The library does not check these.

`invoiceDeliveryStatus` and `deliveryLifecycle` come back when you read delivery notes:

```php
use Firebed\AadeMyData\Http\RequestDocs;

$doc = (new RequestDocs())->handle(mark: '0', dateFrom: '01/01/2026', dateTo: '31/01/2026');

foreach ($doc->getInvoices() ?? [] as $invoice) {
    echo $invoice->getInvoiceDeliveryStatus()?->label();

    foreach ($invoice->getDeliveryLifecycle() ?? [] as $event) {
        echo $event->getEventType()?->label();
        echo $event->getEventTimestamp();
        echo $event->getActorVat();
        echo $event->getTransportDetails()?->getVehicleNumber();
        echo $event->getOutcomeDetails()?->getOutcome()?->label();
        echo $event->getRejectionDetails()?->getReason();
    }
}
```

`DeliveryStatus` cases: `REGISTERED` (1), `CANCELLED` (2), `IN_TRANSIT` (3), `REJECTED` (4),
`DELIVERED_BY_CARRIER` (5), `FAILED_DELIVERY` (7), `COMPLETED` (8), `IN_TRANSIT_RETURN` (9).

> [!TIP]
> See [Digital Goods Movement](../http/digital-goods-movement) for the transfer, delivery and return calls.
