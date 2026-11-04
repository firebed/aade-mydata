# OtherDeliveryNoteHeader

`OtherDeliveryNoteHeader` holds the general delivery details. Set it on the [InvoiceHeader](invoice-header-type)
of a delivery note (e.g. type `9.3`) or of an invoice that is also a delivery note (`isDeliveryNote = true`).

```php
use Firebed\AadeMyData\Models\Address;
use Firebed\AadeMyData\Models\OtherDeliveryNoteHeader;

$loading = new Address();
$loading->setPostalCode('12345');
$loading->setCity('Athens');

$delivery = new Address();
$delivery->setPostalCode('54321');
$delivery->setCity('Thessaloniki');

$deliveryHeader = new OtherDeliveryNoteHeader();
$deliveryHeader->setLoadingAddress($loading);
$deliveryHeader->setDeliveryAddress($delivery);

$header->setOtherDeliveryNoteHeader($deliveryHeader);
```

| Field                  | Type                    | Required | Description                                       |
|------------------------|-------------------------|----------|---------------------------------------------------|
| loadingAddress         | [Address](address-type) | Yes      | Loading address                                   |
| deliveryAddress        | [Address](address-type) | Yes      | Delivery address                                  |
| startShippingBranch    | `int`                   | No       | Issuer branch where the delivery starts           |
| completeShippingBranch | `int`                   | No       | Counterpart branch where the delivery ends        |

## Notes

- Set `startShippingBranch` when the delivery starts from an issuer branch other than the one that issues the note.
- Set `completeShippingBranch` when the delivery ends at a counterpart branch other than the one on the note.
