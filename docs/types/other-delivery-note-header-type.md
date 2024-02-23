# Τύπος OtherDeliveryNoteHeader

Αφορά τα Λοιπά Γενικά Στοιχεία Διακίνησης Εμπορευμάτων. Συμπληρώνεται
για παραστατικά που είναι δελτία αποστολής (π.χ `9.3`) ή τιμολόγιο
και δελτίο αποστολής (`isDeliveryNote = true`).

## Το αντικείμενο αυτό περιέχει τα εξής πεδία:

| Πεδίο                               | Υποχρεωτικό | Περιγραφή                                  |
|-------------------------------------|-------------|--------------------------------------------|
| [**loadingAddress**](address-type)  | **Ναι**     | Διεύθυνση Φόρτωσης                         |
| [**deliveryAddress**](address-type) | **Ναι**     | Διεύθυνση Παράδοσης                        |
| startShippingBranch                 | Όχι         | Εγκατάσταση έναρξης διακίνησης (Εκδότη)    |
| completeShippingBranch              | Όχι         | Εγκατάσταση ολοκλήρωσης διακίνησης (Λήπτη) |

## Παρατηρήσεις

- Με το πεδίο `startShippingBranch` ορίζεται το υποκατάστημα από το οποίο έγινε η
  έναρξη της διακίνησης, σε περίπτωση που η έναρξη της διακίνησης γίνεται από
  κάποιο υποκατάστημα (εγκατάσταση) του εκδότη του παραστατικού, το οποίο είναι
  διαφορετικό από το υποκατάστημα του εκδότη του δελτίου.
- Με το πεδίο `completeShippingBranch` ορίζεται το υποκατάστημα στο οποίο θα
  ολοκληρωθεί η διακίνηση, σε περίπτωση που η διακίνηση θα ολοκληρωθεί σε
  κάποιο υποκατάστημα (εγκατάσταση) του λήπτη του παραστατικού, το οποίο είναι
  διαφορετικό από το υποκατάστημα του λήπτη του δελτίου.

## Παραδείγματα

```php
use Firebed\AadeMyData\Models\OtherDeliveryNoteHeader;
use Firebed\AadeMyData\Models\Address;

// Initialize loading address
$loadingAddress = new Address();
$loadingAddress->setPostalCode('12345');
$loadingAddress->setCity('Αθήνα');

// Initialize delivery address
$deliveryAddress = new Address();
$deliveryAddress->setPostalCode('54321');
$deliveryAddress->setCity('Θεσσαλονίκη');

// Initialize delivery note
$deliveryNote = new OtherDeliveryNoteHeader();
$deliveryNote->setLoadingAddress($loadingAddress);
$deliveryNote->setDeliveryAddress($deliveryAddress);
```