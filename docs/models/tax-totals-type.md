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
  από τον αντίστοιχο πίνακα του Παραρτήματος.
  - `TaxType::WITHHELD_TAX` για τον [Παρακρατούμενο Φόρο](../appendix/withheld-percent-categories)
  - `TaxType::FEES` για τα [Τέλη](../appendix/fees-percent-categories)
  - `TaxType::OTHER_TAXES` για τους [Λοιπούς Φόρους](../appendix/other-taxes-percent-categories)
  - `TaxType::STAMP_DUTY` για το [Ψηφιακό Τέλος Συναλλαγής](../appendix/stamp-categories)
  - `TaxType::DEDUCTIONS` για τις Κρατήσεις
- Το πεδίο `taxCategory` μπορεί να πάρει κάθε φορά οποιαδήποτε τιμή από τον
  αντίστοιχο πίνακα του Παραρτήματος του φόρου που αναφέρεται στο πεδίο
  `taxType`.
- Το πεδίο `underlyingValue` υποδηλώνει την αξία στην οποία εφαρμόζεται ο
  συγκεκριμένος φόρος.

## Παραδείγματα

### Παρακρατούμενος Φόρος (Αμοιβές Συμβουλών Διοίκησης - 20%)

```php
use Firebed\AadeMyData\Models\TaxTotals;
use \Firebed\AadeMyData\Enums\TaxType;
use \Firebed\AadeMyData\Enums\WithheldPercentCategory;

$tax = new TaxTotals();

// Παρακρατούμενος Φόρος
$tax->setTaxType(TaxType::WITHHELD_TAX);

// Περιπτ. δ’ - Αμοιβές Συμβουλών Διοίκησης - 20%
$tax->setTaxCategory(WithheldPercentCategory::MANAGEMENT_CONSULTING_FEES_20);

$tax->setTaxAmount(100);
```

### Τέλη (Τέλος στη συνδρομητική τηλεόραση)

```php
use Firebed\AadeMyData\Models\TaxTotals;
use \Firebed\AadeMyData\Enums\TaxType;
use \Firebed\AadeMyData\Enums\FeesPercentCategory;

$tax = new TaxTotals();

// Τέλη
$tax->setTaxType(TaxType::FEES);

// Τέλος στη συνδρομητική τηλεόραση
$tax->setTaxCategory(FeesPercentCategory::SUBSCRIPTION_TV_10);

$tax->setTaxAmount(52.66);
```

### Λοιποί Φόροι (Ασφάλιστρα κλάδου ζωής 4%)

```php

use Firebed\AadeMyData\Models\TaxTotals;
use \Firebed\AadeMyData\Enums\TaxType;
use \Firebed\AadeMyData\Enums\OtherTaxesPercentCategory;

$tax = new TaxTotals();

// Λοιποί Φόροι
$tax->setTaxType(TaxType::OTHER_TAXES);

// Ασφάλιστρα κλάδου ζωής 4%
$tax->setTaxCategory(OtherTaxesPercentCategory::LIFE_INSURANCE_PREMIUMS_4);

$tax->setTaxAmount(133.56);
```

### Κρατήσεις

Στις κρατήσεις δε χρειάζεται να οριστεί κατηγορία φόρου (`taxCategory`).

```php

use Firebed\AadeMyData\Models\TaxTotals;
use \Firebed\AadeMyData\Enums\TaxType;

$tax = new TaxTotals();

// Λοιποί Φόροι
$tax->setTaxType(TaxType::DEDUCTIONS);

$tax->setTaxAmount(20);
```