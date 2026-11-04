# InvoiceHeader

`InvoiceHeader` holds the header of the invoice: series, number, date, type and the delivery details.

```php
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Models\InvoiceHeader;

$header = new InvoiceHeader();
$header->setSeries('A');
$header->setAa(1);
$header->setIssueDate('2024-02-03');
$header->setInvoiceType(InvoiceType::TYPE_1_1);
$header->setCurrency('EUR');

$invoice->setInvoiceHeader($header);
```

Each field has a setter and a getter named after it (`series` → `setSeries()`, `getSeries()`). The table shows the getter
only when its name is different.

| Field                           | Type                                                                          | Required | Description |
|---------------------------------|-------------------------------------------------------------------------------|----------|-------------|
| series                          | `string`                                                                      | Yes      | Series (max 50). Use `0` when you have no series |
| aa                              | `string` or `int`                                                             | Yes      | Invoice number (max 50). The getter returns `string` |
| issueDate                       | `string`                                                                      | Yes      | Issue date (`Y-m-d`) |
| invoiceType                     | [`InvoiceType`](../appendix/invoice-types)                                    | Yes      | Invoice type |
| vatPaymentSuspension            | `bool`                                                                        | No       | VAT payment suspension. Getter `isVatPaymentSuspension()` |
| currency                        | `CurrencyCode` or `string`                                                    | No       | Currency, ISO 4217. The getter returns `string` |
| exchangeRate                    | `float`                                                                       | No       | Exchange rate to EUR (min 0, 5 decimals). Only when currency is not EUR |
| correlatedInvoices              | `int[]`                                                                       | No       | MARKs of correlated invoices. Add one with `addCorrelatedInvoice()` |
| selfPricing                     | `bool`                                                                        | No       | Self-billing invoice. Getter `isSelfPricing()` |
| dispatchDate                    | `string`                                                                      | No       | Dispatch start date (`Y-m-d`) |
| dispatchTime                    | `string`                                                                      | No       | Dispatch start time (`H:i:s`) |
| vehicleNumber                   | `string`                                                                      | No       | Vehicle number (max 150) |
| movePurpose                     | [`MovePurpose`](../appendix/move-purpose)                                     | No       | Purpose of the movement |
| fuelInvoice                     | `bool`                                                                        | No       | Liquid fuel sale invoice, provider channel only. Getter `isFuelInvoice()` |
| specialInvoiceCategory          | [`SpecialInvoiceCategory`](../appendix/special-invoice-categories)            | No       | Special invoice category |
| invoiceVariationType            | [`InvoiceVariationType`](../appendix/invoice-variation-types)                 | No       | Invoice variation type |
| otherCorrelatedEntities         | [`EntityType[]`](entity-type)                                                 | No       | Other related entities. Add one with `addOtherCorrelatedEntities()` |
| otherDeliveryNoteHeader         | [OtherDeliveryNoteHeader](other-delivery-note-header-type)                    | No       | General delivery details |
| isDeliveryNote                  | `bool`                                                                        | No       | The invoice is also a delivery note. Getter `getIsDeliveryNote()` |
| otherMovePurposeTitle           | `string`                                                                      | No       | Title of the other movement purpose (max 150) |
| thirdPartyCollection            | `bool`                                                                        | No       | Collection on behalf of third parties |
| multipleConnectedMarks          | `int[]`                                                                       | No       | Connected MARKs. Add one with `addMultipleConnectedMark()`. Not allowed on types 1.6, 2.4 and 5.1 |
| tableAA                         | `string`                                                                      | No       | Table number (max 50). Only for type 8.6 |
| totalCancelDeliveryOrders       | `bool`                                                                        | No       | Cancels all orders in `multipleConnectedMarks`. Only for type 8.6 |
| reverseDeliveryNote             | `bool`                                                                        | No       | Reverse delivery note |
| reverseDeliveryNotePurpose      | [`ReverseDeliveryNotePurpose`](../appendix/reverse-delivery-note-purpose)     | No       | Purpose of the reverse delivery note |
| toWeigh                         | `bool`                                                                        | No       | Goods to be weighed |
| receivingNotePurpose            | [`ReceivingNotePurpose`](../appendix/receiving-note-purpose)                  | No       | Purpose of the receiving note. Required for types 10.1 and 10.2 |
| otherReceivingNotePurposeTitle  | `string`                                                                      | No       | Title of the other receiving purpose (max 150) |
| nonObligatedRecipient           | `bool`                                                                        | No       | Recipient not obliged. Getter `isNonObligatedRecipient()` |
| withoutDigitalTransportTracking | `bool`                                                                        | No       | No digital transport tracking. Getter `isWithoutDigitalTransportTracking()` |

`issueTime` (`setIssueTime('14:30:00')`) is not a myDATA field and never goes into the XML. Gateways that send through an
e-invoicing provider read it to build the issue date and time.

## Notes

- `correlatedInvoices` is a list of MARKs.
- `isDeliveryNote = true` makes an invoice (e.g. type `1.1`) also a delivery note. Then you must send the delivery
  details too.
- Fill `otherMovePurposeTitle` when `movePurpose` is `MovePurpose::OTHER_MOVEMENTS` (19).
- `thirdPartyCollection` marks a business that collects (type `8.4`, POS receipt) or refunds (type `8.5`, POS refund)
  payments on behalf of third parties.
- `multipleConnectedMarks` lists many connected MARKs, e.g. one invoice or receipt for many restaurant order slips.
- With `totalCancelDeliveryOrders = true`, a type `8.6` document cancels all order slips (also type `8.6`) listed in
  `multipleConnectedMarks`. Send it with a single row, zero amounts and VAT category 8 (`VatCategory::WITHOUT_VAT`).
- `reverseDeliveryNote` marks a reverse delivery note on type `9.3`. Fill `reverseDeliveryNotePurpose` with it.
- Fill `otherReceivingNotePurposeTitle` only when `receivingNotePurpose` is `ReceivingNotePurpose::OTHER_CASES` (7).
- `nonObligatedRecipient` and `withoutDigitalTransportTracking` apply to delivery notes. They cannot both be `true`
  (error 290).
