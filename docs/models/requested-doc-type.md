# Τύπος RequestedDoc

Στις περιπτώσεις που ο χρήστης καλέσει μια εκ των δυο μεθόδων λήψης δεδομένων
(`\Firebed\AadeMyData\Http\RequestDocs`, `\Firebed\AadeMyData\Http\RequestTransmittedDocs`), θα λάβει 
ένα αντικείμενο `\Firebed\AadeMyData\Models\RequestedDoc`.
Το αντικείμενο θα περιλαμβάνει λίστες οι οποίες έχουν mark μεγαλύτερο από αυτό που εισήχθη ως παράμετρο.

Σε περίπτωση που ο όγκος των δεδομένων υπερβαίνει το επιτρεπτό όριο και η λήψη τους γίνει τμηματικά
το αντικείμενο θα περιλαμβάνει το στοιχείο continuationToken το οποίο θα περιέχει τις παραμέτρους
`nextPartitionKey` και `nextRowKey`.

## Περιγραφή

| Πεδίο                      | Περιγραφή                                                          |
|----------------------------|--------------------------------------------------------------------|
| continuationToken          | Στοιχείο για την τμηματική λήψη αποτελεσμάτων                      |
| invoicesDoc                | Λίστα Παραστατικών ValidationError, TechnicalError, XMLSyntaxError |
| cancelledInvoicesDoc       | Λίστα ακυρώσεων                                                    |
| invoiceMark                | ΜΑΡΚ παραστατικού που ακυρώθηκε                                    |
| cancellationMark           | ΜΑΡΚ ακύρωσης                                                      |
| cancellationDate           | Ημερομηνία ακύρωσης                                                |
| incomeClassificationsDoc   | Λίστα Χαρακτηρισμών Εσόδων                                         |
| expensesClassificationsDoc | Λίστα Χαρακτηρισμών Εξόδων                                         |
| paymentMethodsDoc          | Λίστα Τρόπων Πληρωμής                                              |
| nextPartitionKey           | Παράμετρος για επόμενη κλήση λήψης                                 |
| nextRowKey                 | Παράμετρος για επόμενη κλήση λήψης                                 |

## Παρατηρήσεις

- Σε περίπτωση που θα επιστρέφεται το στοιχείο continuationToken τα πεδία
  nextPartitionKey και nextRowKey θα είναι συμπληρωμένα από την υπηρεσία και
  χρησιμοποιούνται στην επόμενη κλήση της ίδιας μεθόδου που είχε καλεστεί από
  τον χρήστη

## `Firebed\AadeMyData\Models\RequestedDoc`

```php
use Firebed\AadeMyData\Models\RequestedDoc;

$requestedDoc = new RequestedDoc();

// Για την περίπτωση που η λήψη των δεδομένων γίνεται τμηματικά
$continuationToken = $requestedDoc->getContinuationToken();

// Λήψη λίστας παραστατικών
$invoices = $requestedDoc->getInvoices();

// Λίστα ακυρωμένων παραστατικών
$cancelledInvoices = $requestedDoc->getCancelledInvoices();

// Λίστα χαρακτηρισμών εσόδων
$incomeClassifications = $requestedDoc->getIncomeClassifications();

// Λίστα χαρακτηρισμών εξόδων
$expensesClassifications = $requestedDoc->getExpensesClassifications();

// Λίστα τρόπων πληρωμής
$paymentMethods = $requestedDoc->getPaymentMethods();
```