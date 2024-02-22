# Τύπος ProviderSignature

Ο τύπος `ProvidersSignature` χρησιμοποιείται για την αναπαράσταση των στοιχείων της υπογραφής των παρόχων.

## Το αντικείμενο αυτό περιέχει τα εξής πεδία:

| Πεδίο         | Υποχρεωτικό | Περιγραφή                                |
|---------------|-------------|------------------------------------------|
| SigningAuthor | **Ναι**     | Αριθμός Απόφασης έγκρισης ΥΠΑΗΕΣ Παρόχου |
| Signature     | **Ναι**     | Υπογραφή                                 |

## Παρατηρήσεις

- Λεπτομέρειες `Signature`  στην υπ’ αριθμ. Α. 1155/09-10-2023 απόφαση
  (ΦΕΚ 5992 Β΄/13.10.2023), όπως ισχύει.

## Παραδείγματα

```php
use Firebed\AadeMyData\Models\ProvidersSignature;

$providerSignature = new ProvidersSignature();
$providerSignature->setSigningAuthor('1234567890');
$providerSignature->setSignature('123456');
```