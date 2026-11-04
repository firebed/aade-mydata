# Party

`Party` holds the details of a person or business. The invoice issuer is an `Issuer` and the recipient is a
`Counterpart`. Both extend `Party` and have the same fields. Use `Party` itself in
[EntityType](entity-type).

```php
use Firebed\AadeMyData\Models\Address;
use Firebed\AadeMyData\Models\Counterpart;
use Firebed\AadeMyData\Models\Issuer;

$issuer = new Issuer();
$issuer->setVatNumber('888888888');
$issuer->setCountry('GR');
$issuer->setBranch(0);

$counterpart = new Counterpart();
$counterpart->setVatNumber('DE123456789');
$counterpart->setCountry('DE');
$counterpart->setBranch(0);
$counterpart->setName('Example GmbH');
$counterpart->setAddress(new Address([
    'street' => 'Hauptstrasse',
    'number' => '10',
    'postalCode' => '10115',
    'city' => 'Berlin',
]));

$invoice->setIssuer($issuer);
$invoice->setCounterpart($counterpart);
```

| Field             | Type                                   | Required | Description                                      |
|-------------------|----------------------------------------|----------|--------------------------------------------------|
| vatNumber         | `string`                               | Yes      | Any valid VAT number (max 30)                    |
| country           | `CountryCode` or `string`              | Yes      | Country code, ISO 3166 (2 letters). The getter returns `string` |
| branch            | `int`                                  | Yes      | Branch number. `0` for the head office or when there is no branch |
| name              | `string`                               | No       | Name (max 200)                                   |
| address           | [Address](address-type)                | No       | Address                                          |
| documentIdNo      | `string`                               | No       | Official document number, e.g. passport (max 100) |
| supplyAccountNo   | `string`                               | No       | Electricity supply number (max 100)              |
| countryDocumentId | `CountryCode` or `string`              | No       | Country that issued the official document        |

## Notes

- For an issuer in Greece (`GR`), myDATA does not accept `name` and `address`.
  For a counterpart in Greece, it does not accept `name`.
- `documentIdNo` and `countryDocumentId` are allowed only for tax free invoices
  (`specialInvoiceCategory` = 4) and describe the counterpart. `countryDocumentId` needs `documentIdNo`.
- `supplyAccountNo` is allowed only on fuel invoices (`fuelInvoice = true`, provider channel only) and describes the counterpart.
