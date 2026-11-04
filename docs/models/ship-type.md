# Ship

`Ship` is the activity declaration of a ship (POL 1177/2018 art. 27). You set it on an invoice row as `dienergia`.

```php
use Firebed\AadeMyData\Models\InvoiceDetails;
use Firebed\AadeMyData\Models\Ship;

$ship = new Ship();
$ship->setApplicationId('1234567890');
$ship->setApplicationDate('2023-10-13');
$ship->setDoy('Α΄ Αθηνών');
$ship->setShipId('123456');

$row = new InvoiceDetails();
$row->setDienergia($ship);
```

| Field           | Type     | Required | Description                     |
|-----------------|----------|----------|---------------------------------|
| applicationId   | `string` | Yes      | Activity declaration number     |
| applicationDate | `string` | Yes      | Declaration date (`Y-m-d`)      |
| doy             | `string` | No       | Tax office (DOY) of the declaration (max 150) |
| shipId          | `string` | Yes      | Ship details                    |

> [!TIP]
> `InvoiceDetails::setDienergia()` also takes the four values directly:
> `$row->setDienergia('1234567890', '2023-10-13', 'Α΄ Αθηνών', '123456')`.
