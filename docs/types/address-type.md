# Τύπος Address

Ο τύπος `Address` αναπαριστά μια διεύθυνση φυσικού τόπου και αφορά εκδότη ή λήπτη.

## Το αντικείμενο αυτό περιέχει τα εξής πεδία:

| Πεδίο      | Υποχρεωτικό | Περιγραφή    |
|------------|-------------|--------------|
| street     | Όχι         | Οδός         |
| number     | Όχι         | Αριθμός οδού |
| postalCode | **Ναι**     | ΤΚ           |
| city       | **Ναι**     | Πόλη         |

```php
use Firebed\AadeMyData\Models\Address;

$address = new Address();
$address->setStreet('Λεωφόρος Βασιλίσσης Σοφίας');
$address->setNumber('1');
$address->setPostalCode('11521');
$address->setCity('Αθήνα');
```