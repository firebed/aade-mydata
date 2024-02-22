# Τύπος ExpensesClassification

Ο τύπος `ExpensesClassification` αποτελεί τη βασική δομή του Χαρακτηρισμού Εξόδων
και εμπεριέχεται είτε σε κάθε γραμμής του παραστατικού ξεχωριστά (χαρακτηρισμός γραμμής),
είτε στην περίληψη παραστατικού (άθροισμα χαρακτηρισμών ανά τύπο - κατηγορία), είτε στο αντικείμενο
`InvoiceExpensesClassification` όταν οι χαρακτηρισμοί εσόδων υποβάλλονται ξεχωριστά.

## Το αντικείμενο αυτό περιέχει τα εξής πεδία:

| Πεδίο                                                              | Υποχρεωτικό | Περιγραφή                   |
|--------------------------------------------------------------------|-------------|-----------------------------|
| [**classificationType**](../appendix/expenses-classifications)     | Όχι         | Κωδικός Χαρακτηρισμού       |
| [**classificationCategory**](../appendix/expenses-classifications) | Όχι         | Κατηγορία Χαρακτηρισμού     |
| amount                                                             | **Ναι**     | Ποσό ΦΠΑ                    |
| vatAmount                                                          | Όχι         | Ποσό                        |
| [**vatCategory**](../appendix/vat-categories)                      | Όχι         | Κατηγορία ΦΠΑ               |
| [**vatExemptionCategory**](../appendix/vat-exemption-categories)   | Όχι         | Κατηγορία Εξαίρεσης ΦΠΑ     |
| id                                                                 | Όχι         | Αύξων αριθμός Χαρακτηρισμού |

## Παρατηρήσεις

- Το πεδίο id προσφέρεται για σειριακή αρίθμηση (1,2,3… κλπ) των χαρακτηρισμών
  εντός μιας γραμμής
- Τα πεδία `vatAmount`, `vatCategory`, `vatExemptionCategory` χρησιμοποιούνται μόνο
  για τους χαρακτηρισμούς εξόδων ΦΠΑ, διαφορετικά αγνοούνται.
- Το πεδίο `classificationCategory` χρησιμοποιείται μόνο για τους χαρακτηρισμούς
  εξόδων Ε3, αλλιώς αγνοείται.

```php
use Firebed\AadeMyData\Models\ExpensesClassification;
use Firebed\AadeMyData\Enums\ExpenseClassificationType;
use Firebed\AadeMyData\Enums\ExpenseClassificationCategory;

$ecls = new ExpensesClassification();
$ecls->ExpensesClassification(ExpenseClassificationType::E3_101); // Εμπορεύματα έναρξης
$ecls->setClassificationCategory(ExpenseClassificationCategory::CATEGORY_2_1); // Αγορές Εμπορευμάτων
$ecls->setAmount(45);
```