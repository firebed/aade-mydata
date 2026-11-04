# Address

`Address` is the address of an issuer, a recipient or a loading/delivery place.

```php
use Firebed\AadeMyData\Models\Address;

$address = new Address();
$address->setStreet('Vasilissis Sofias');
$address->setNumber('1');
$address->setPostalCode('11521');
$address->setCity('Athens');
```

| Field      | Type     | Required | Description            |
|------------|----------|----------|------------------------|
| street     | `string` | No       | Street (max 150)       |
| number     | `string` | No       | Street number          |
| postalCode | `string` | Yes      | Postal code            |
| city       | `string` | Yes      | City (max 150)         |

Used by [Party](party-type) and [OtherDeliveryNoteHeader](other-delivery-note-header-type).
