# Τύπος TaxTotals

Ο τύπος `TaxTotals` περιγράφει τα στοιχεία των φόρων που αφορούν σε ένα παραστατικό.

## Το αντικείμενο αυτό περιέχει τα εξής πεδία:

| Πεδίο                         | Υποχρεωτικό | Περιγραφή             |
|-------------------------------|-------------|-----------------------|
| taxType                       | **Ναι**     | Είδος Φόρου           |
| [**taxCategory**](party-type) | Όχι         | Κατηγορία Φόρου       |
| underlyingValue               | Όχι         | Υποκείμενη Αξία       |
| taxAmount                     | **Ναι**     | Ποσό Φόρου            |
| id                            | Όχι         | Αύξων αριθμός γραμμής |

## Παρατηρήσεις

- Το πεδίο `taxType` περιέχει τον κωδικό του φόρου. Μπορεί να πάρει κάθε τιμή
  από τον αντίστοιχο πίνακα του Παραρτήματος του φόρου.
  - `TaxType::TYPE_1` για τον Παρακρατούμενο Φόρο
  - `TaxType::TYPE_2` για τα Τέλη
  - `TaxType::TYPE_3` για τους Λοιπούς Φόρους
  - `TaxType::TYPE_4` για τα Χαρτόσημα
  - `TaxType::TYPE_5` για τις Κρατήσεις
- Το πεδίο `taxCategory` μπορεί να πάρει κάθε φορά οποιαδήποτε τιμή από τον
  αντίστοιχο πίνακα του Παραρτήματος του φόρου που αναφέρεται στο πεδίο
  taxType.
- Το πεδίο `underlyingValue` υποδηλώνει την αξία στην οποία εφαρμόζεται ο
  συγκεκριμένος φόρος.

## Παραδείγματα

```php
use Firebed\AadeMyData\Models\TaxTotals;
use \Firebed\AadeMyData\Enums\TaxType;
use \Firebed\AadeMyData\Enums\WithheldPercentCategory;

$tax = new TaxTotals();
$tax->setTaxType(TaxType::TYPE_1); // Παρακρατούμενος Φόρος
$tax->setTaxCategory(WithheldPercentCategory::TAX_3); // Περιπτ. δ’ - Αμοιβές Συμβουλών Διοίκησης - 20%
$tax->setTaxAmount(100);
```