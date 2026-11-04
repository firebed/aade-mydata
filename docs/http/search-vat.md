# VAT number lookup

Look up a business by VAT number, to check its tax and business status. This is a separate package.

```bash
composer require firebed/vat-registry
```
[View Vat Registry on GitHub](https://github.com/firebed/vat-registry)

There are two services:

| Service | Covers | Needs credentials |
|---|---|---|
| AADE business registry (`TaxisNet`) | Every Greek VAT number | Yes |
| VIES (`VIES`) | VAT numbers of every EU member state, fewer details | No |

## AADE business registry

You need a `username` and a `password`:

1. Register for the [service](https://www1.aade.gr/webtax/wspublicreg/faces/pages/wspublicreg/menu.xhtml) with your TAXISnet credentials.
2. Get special access codes from [Special Codes Management](https://www1.aade.gr/sgsisapps/tokenservices/protected/displayConsole.htm).

More on the [AADE page](https://www.aade.gr/anazitisi-basikon-stoiheion-mitrooy-epiheiriseon).

```php
use Firebed\VatRegistry\TaxisNet;
use Firebed\VatRegistry\VatException;

$username = 'your-username';
$password = 'your-password';

$taxis = new TaxisNet($username, $password);

try {
    $response = $taxis->handle('094014201');
    
    dd($response);
} catch (VatException $exception) {
    echo "Error: " . $exception->getMessage();
}
```

Result:

```php
Firebed\VatRegistry\VatEntity {
  +vatNumber: "094014201"
  +tax_authority_id: "1159"
  +tax_authority_name: "ΦΑΕ ΑΘΗΝΩΝ"
  +flag_description: "ΜΗ ΦΠ"
  +valid: true
  +validity_description: "ΕΝΕΡΓΟΣ ΑΦΜ"
  +firm_flag_description: "ΕΠΙΤΗΔΕΥΜΑΤΙΑΣ"
  +legalName: "ΤΡΑΠΕΖΑ ΕΘΝΙΚΗ ΤΗΣ ΕΛΛΑΔΟΣ ΑΝΩΝΥΜΗ ΕΤΑΙΡΕΙΑ"
  +commerce_title: ""
  +legal_status_description: "ΑΕ"
  +street: "ΑΙΟΛΟΥ"
  +street_number: "86"
  +postcode: "10559"
  +city: "ΑΘΗΝΑ"
  +registration_date: "1900-01-01"
  +stop_date: ""
  +normal_vat: true
  +firms: array:2 [
    0 => array:4 [
      "code" => "64191204"
      "description" => "ΥΠΗΡΕΣΙΕΣ ΤΡΑΠΕΖΩΝ"
      "kind" => "1"
      "kind_description" => "ΚΥΡΙΑ"
    ]
    1 => array:4 [
      "code" => "66221001"
      "description" => "ΥΠΗΡΕΣΙΕΣ ΑΣΦΑΛΙΣΤΙΚΟΥ ΠΡΑΚΤΟΡΑ ΚΑΙ ΑΣΦΑΛΙΣΤΙΚΟΥ ΣΥΜΒΟΥΛΟΥ"
      "kind" => "2"
      "kind_description" => "ΔΕΥΤΕΡΕΥΟΥΣΑ"
    ]
  ]
}
```

The values come from AADE, so they are in Greek.

An invalid VAT number returns `null`. Any other problem throws `VatException` with the error message.

## VIES

VIES checks a VAT number of any EU member state. It is free and needs no registration.
Pass the country code (e.g. `EL` for Greece) and the VAT number.

```php
use Firebed\VatRegistry\VIES;
use Firebed\VatRegistry\VatException;

$vies = new VIES();

try {
    $response = $vies->handle('EL', '094014201');
    
    dd($response);
} catch (VatException $exception) {
    echo "Error: " . $exception->getMessage();
}
```

Result:

```php
Firebed\VatRegistry\VatEntity {
  +vatNumber: "094014201"
  +tax_authority_id: null
  +tax_authority_name: null
  +flag_description: null
  +valid: true
  +validity_description: null
  +firm_flag_description: null
  +legalName: "ΤΡΑΠΕΖΑ ΕΘΝΙΚΗ ΤΗΣ ΕΛΛΑΔΟΣ ΑΝΩΝΥΜΗ ΕΤΑΙΡΕΙΑ"
  +commerce_title: null
  +legal_status_description: null
  +street: "ΑΙΟΛΟΥ"
  +street_number: "86"
  +postcode: "10559"
  +city: "ΑΘΗΝΑ"
  +registration_date: null
  +stop_date: null
  +normal_vat: null
  +firms: []
}
```

An invalid VAT number returns `null`.

## Helper methods

### Natural person or company

```php
$entity->isNaturalPerson();
$entity->isCompany();
```

### Active business

```php
// true: the business is active
// false: the business has stopped
$entity->isActive();
```
