# Τύπος InvoiceSummary

Το αντικείμενο `InvoiceSummary` περιέχει τα στοιχεία των συνόλων του τιμολογίου.

## Το αντικείμενο αυτό περιέχει τα εξής πεδία:

| Πεδίο                                                        | Υποχρεωτικό | Περιγραφή                         |
|--------------------------------------------------------------|-------------|-----------------------------------|
| totalNetValue                                                | **Ναι**     | Σύνολο Καθαρής Αξίας              |
| totalVatAmount                                               | **Ναι**     | Σύνολο ΦΠΑ                        |
| totalWithheldAmount                                          | **Ναι**     | Σύνολο Παρακρατήσεων Φόρων        |
| totalFeesAmount                                              | **Ναι**     | Σύνολο Τελών                      |
| totalStampDutyAmount                                         | **Ναι**     | Σύνολο Ψηφιακού Τέλους Συναλλαγής |
| totalOtherTaxesAmount                                        | **Ναι**     | Σύνολο Λοιπών Φόρων               |
| totalDeductionsAmount                                        | **Ναι**     | Σύνολο Κρατήσεων                  |
| totalGrossValue                                              | **Ναι**     | Συνολική Αξία                     |
| [**incomeClassification[]**](income-classification-type)     | Όχι         | Χαρακτηρισμοί Εσόδων              |
| [**expensesClassification[]**](expenses-classification-type) | Όχι         | Χαρακτηρισμοί Εξόδων              |

## Παρατηρήσεις

- Όλα τα ποσά έχουν ελάχιστη τιμή 0 και μπορούν να περιέχουν δύο δεκαδικά ψηφία.
- Τα στοιχεία `incomeClassification` και `expensesClassification` περιέχουν τα
  αθροίσματα για κάθε συνδυασμό τιμών των πεδίων `classificationType` και
  `classificationCategory` που εντοπίζονται στις γραμμές του παραστατικού.
- Όλα τα πεδία αθροισμάτων φόρων εκτός του `totalVatAmount` θα περιέχουν είτε τα
  αθροίσματα των αντίστοιχων φόρων των γραμμών του παραστατικού, είτε τα
  αθροίσματα των αντίστοιχων φόρων που περιέχονται στο στοιχείο `taxesTotals`.

## Παραδείγματα

```php
use Firebed\AadeMyData\Models\InvoiceSummary;
use Firebed\AadeMyData\Models\IncomeClassification;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;

$summary = new InvoiceSummary();
$summary->setTotalNetValue(50);
$summary->setTotalVatAmount(12);
$summary->setTotalWithheldAmount(6);
$summary->setTotalFeesAmount(0);
$summary->setTotalStampDutyAmount(0);
$summary->setTotalOtherTaxesAmount(0);
$summary->setTotalDeductionsAmount(0);
$summary->setTotalGrossValue(56); // 50 + 12 - 6 = 56

$icls = new IncomeClassification();
$icls->setClassificationType(IncomeClassificationType::TYPE_1);
$icls->setClassificationCategory(IncomeClassificationCategory::CATEGORY_1_1);
$icls->setAmount(50);
$summary->addIncomeClassification($icls);
```

Μπορείτε επίσης να ορίσετε τους χαρακτηρισμούς εσόδων και εξόδων απευθείας χωρίς τη χρήση του αντικειμένου `IncomeClassification` ή `ExpensesClassification` ως εξής:

```php
$summary->addIncomeClassification(
    IncomeClassificationType::TYPE_1, 
    IncomeClassificationCategory::CATEGORY_1_1, 
    50
);
```