# Τύπος IncomeClassification

Ο τύπος `IncomeClassification` αποτελεί τη βασική δομή του Χαρακτηρισμού Εσόδων
και εμπεριέχεται είτε σε κάθε γραμμής του παραστατικού ξεχωριστά (χαρακτηρισμός γραμμής),
είτε στην περίληψη παραστατικού (άθροισμα χαρακτηρισμών ανά τύπο - κατηγορία),
είτε στο αντικείμενο `InvoiceIncomeClassification` όταν οι χαρακτηρισμοί εσόδων υποβάλλονται ξεχωριστά.

## Το αντικείμενο αυτό περιέχει τα εξής πεδία:

| Πεδίο                                                            | Υποχρεωτικό | Περιγραφή                   |
|------------------------------------------------------------------|-------------|-----------------------------|
| [**classificationType**](../appendix/income-classifications)     | Όχι         | Κωδικός Χαρακτηρισμού       |
| [**classificationCategory**](../appendix/income-classifications) | **Ναι**     | Κατηγορία Χαρακτηρισμού     |
| amount                                                           | **Ναι**     | Ποσό                        |
| id                                                               | Όχι         | Αύξων αριθμός Χαρακτηρισμού |

## Παρατηρήσεις

- Το πεδίο id προσφέρεται για σειριακή αρίθμηση (1,2,3… κλπ) των χαρακτηρισμών
  εντός μιας γραμμής

```php
use Firebed\AadeMyData\Models\IncomeClassification;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;

$icls = new IncomeClassification();
$icls->setClassificationType(IncomeClassificationType::E3_561_001); // Πωλήσεις αγαθών και υπηρεσιών Χονδρικές - Επιτηδευματιών
$icls->setClassificationCategory(IncomeClassificationCategory::CATEGORY_1_1); // Έσοδα από Πώληση Εμπορευμάτων
$icls->setAmount(45);
```