# Τύπος Party

Ο εκδότης (`issuer`) και ο λήπτης (`counterpart`) του παραστατικού είναι στοιχεία τύπου `Party` και η δομή τους
περιγράφεται παρακάτω:

## Το αντικείμενο αυτό περιέχει τα εξής πεδία:

| Πεδίο                         | Υποχρεωτικό | Περιγραφή                               |
|-------------------------------|-------------|-----------------------------------------|
| vatNumber                     | **Ναι**     | Οποιοσδήποτε έγκυρος ΑΦΜ                |
| country                       | **Ναι**     | Κωδικός Χώρας                           |
| branch                        | **Ναι**     | Αριθμός Εγκατάστασης (ελάχιστη τιμή 0)  |
| name                          | Όχι         | Επωνυμία                                |
| [**address**](./address-type) | Όχι         | Διεύθυνση                               |
| documentIdNo                  | Όχι         | Αριθμός επίσημου εγγράφου               |
| supplyAccountNo               | Όχι         | Αριθμός Παροχής Ηλ. Ρεύματος            |
| countryDocumentId             | Όχι         | Κωδικός Χώρας Έκδοσης Επίσημου Εγγράφου |

## Παρατηρήσεις

- Ο κωδικός της χώρας είναι δύο χαρακτήρες και προέρχεται από την αντίστοιχη
  λίστα χωρών όπως περιγράφεται στο **ISO 3166**.
- Σε περίπτωση που η εγκατάσταση του εκδότη είναι η έδρα ή δεν υφίσταται, το
  πεδίο `branch` πρέπει να έχει την τιμή 0
- Για τον εκδότη, τα στοιχεία Επωνυμία και Διεύθυνση δε γίνονται αποδεκτά στην
  περίπτωση που αφορούν οντότητα εντός Ελλάδας (GR). Για τον λήπτη, το στοιχείο
  Επωνυμία δε γίνονται αποδεκτό στην περίπτωση που αφορά οντότητα εντός
  Ελλάδας (GR).
- Ο αριθμός επίσημου εγγράφου, είναι επιτρεπτός μόνο στην περίπτωση διαβίβασης
  παραστατικών που ανήκουν στην Ειδική Κατηγορία Παραστατικού Tax free (το
  πεδίο της επικεφαλίδας του παραστατικού `specialInvoiceCategory` έχει την τιμή 4),
  και μπορεί να είναι οποιοδήποτε επίσημο έγγραφο ταυτοποίησης (π.χ αριθμός
  διαβατηρίου) του λήπτη του παραστατικού.
- Ο αριθμός Παροχής Ηλ. Ρεύματος, είναι επιτρεπτός μόνο στην περίπτωση
  διαβίβασης παραστατικών καυσίμων (το πεδίο της επικεφαλίδας του παραστατικού
  `fuelInvoice` έχει την τιμή true – αποδεκτό μόνο για διαβίβαση από παρόχους) και
  είναι πληροφορία του λήπτη του παραστατικού.
- Ο κωδικός χώρας έκδοσης του επίσημου εγγράφου (π.χ διαβατηρίου), είναι
  επιτρεπτός μόνο στην περίπτωση διαβίβασης παραστατικών που ανήκουν στην
  Ειδική Κατηγορία Παραστατικού Tax free (το πεδίο της επικεφαλίδας του
  παραστατικού `specialInvoiceCategory` έχει την τιμή 4) και εφόσον έχει συμπληρωθεί
  το πεδίο αριθμός επίσημου εγγράφου (`documentIdNo`) και αφορά τον λήπτη του
  παραστατικού.

## Παραδείγματα

```php
use Firebed\AadeMyData\Models\Issuer;
use Firebed\AadeMyData\Models\Counterpart;
use Firebed\AadeMyData\Models\Party;

// Εκδότης παραστατικού
$issuer = new Issuer();
$issuer->setVatNumber('123456789');
$issuer->setCountry('GR');
$issuer->setBranch(0);

// Λήπτης παραστατικού
$counterpart = new Counterpart();
$counterpart->setVatNumber('123456789');
$counterpart->setCountry('GR');
$counterpart->setBranch(0);

// Για χρήση σε άλλες περιπτώσεις όπως `EntityType`
$party = new Party();
$party->setVatNumber('123456789');
$party->setCountry('GR');
$party->setBranch(0);
```