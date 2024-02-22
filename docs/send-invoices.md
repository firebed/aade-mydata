---
title: Διαβίβαση παραστατικών - ΑΑΔΕ myDATA REST API
meta: Διεπαφή για το ΑΑΔΕ myDATA για αποστολή και λήψη παραστατικών, ακύρωση παραστατικών, χαρακτηρισμός και λήψη εσόδων και εξόδων και λήψη αναφορών ΦΠΑ.
prev: installation|Εγκατάσταση
next: send-income-classification|SendIncomeClassification
---

# Διαβίβαση παραστατικών

Υπάρχουν διάφοροι τρόποι για τη διαβίβαση παραστατικών στο σύστημα του ΑΑΔΕ myDATA. 

## Αποστολή παραστατικών

Για την αποστολή παραστατικών στο σύστημα του ΑΑΔΕ myDATA, χρησιμοποιήστε η κλάση `Firebed\AadeMyData\Http\SendInvoices`.
Η κλάση αυτή δέχεται ως παράμετρο:
- είτε ένα αντικείμενο `Firebed\AadeMyData\Models\InvoicesDoc`
- είτε ένα αντικείμενο `Firebed\AadeMyData\Models\Invoice` 
- είτε έναν πίνακα από αντικείμενα `Firebed\AadeMyData\Models\Invoice`.

> ### Σημείωση
> Στα παρακάτω παραδείγματα δημιουργούμε κενά αντικείμενα `Firebed\AadeMyData\Models\Invoice` για χάριν συντομίας. Κανονικά θα πρέπει να 
συμπληρώσουμε όλα τα απαραίτητα πεδία του παραστατικού πριν την αποστολή του.
> [**Δείτε περισσότερα**](/types/invoice-type)

### Αποστολή ενός μόνο παραστατικού

Η διαβίβαση ενός μόνο παραστατικού είναι μια από τις πιο συχνές περιπτώσεις χρήσης. Στο παρακάτω παράδειγμα, δημιουργούμε ένα αντικείμενο `Firebed\AadeMyData\Models\Invoice` και το διαβιβάζουμε στο σύστημα του ΑΑΔΕ myDATA.

```php
use Firebed\AadeMyData\Http\SendInvoices;
use Firebed\AadeMyData\Models\InvoicesDoc;
use Firebed\AadeMyData\Models\Invoice;

$invoice = new Invoice();
$request = new SendInvoices();
$response = $request->handle($invoice);
```

### Αποστολή πολλών παραστατικών με τη χρήση πίνακα αντικειμένων `Invoice`

Αρκετές φορές χρειαζόμαστε να διαβιβάσουμε πολλά παραστατικά ταυτόχρονα. Στο παρακάτω παράδειγμα,
δημιουργούμε τρία αντικείμενα `Firebed\AadeMyData\Models\Invoice` και τα διαβιβάζουμε ταυτόχρονα στο σύστημα του ΑΑΔΕ myDATA.

```php
$invoice1 = new Invoice();
$invoice2 = new Invoice();
$invoice3 = new Invoice();
$request = new SendInvoices();
$response = $request->handle([$invoice1, $invoice2, $invoice3]);
```

### Αποστολή πολλών παραστατικών με τη χρήση ενός αντικειμένου `InvoicesDoc`

Μπορούμε επίσης να δημιουργήσουμε ένα αντικείμενο `Firebed\AadeMyData\Models\InvoicesDoc` και να προσθέσουμε πολλά παραστατικά σε αυτό.
Στο παρακάτω παράδειγμα, δημιουργούμε ένα αντικείμενο `Firebed\AadeMyData\Models\InvoicesDoc` με 3 παραστατικά στον κατασκευαστή (constructor)
και έπειτα προσθέτουμε άλλα 2 αντικείμενα `Firebed\AadeMyData\Models\Invoice` σε αυτό. Σύνολο 5 παραστατικά.

```php
$doc = new InvoicesDoc([new Invoice(), new Invoice(), new Invoice()]);
$doc->add(new Invoice())
$doc->add(new Invoice())

$request = new SendInvoices();
$response = $request->handle($doc);

// Alternatively
$doc = new InvoicesDoc([new Invoice(), new Invoice()]);
$request = new SendInvoices();
$response = $request->handle($doc);
```

## Λήψη αποτελεσμάτων

Το αποτέλεσμα της αποστολής παραστατικών είναι ένα αντικείμενο `Firebed\AadeMyData\Models\ResponseDoc` το οποίο περιέχει τόσα αντικείμενα
`Firebed\AadeMyData\Models\Response` όσα και τα παραστατικά που διαβιβάστηκαν. Κάθε αντικείμενο `Firebed\AadeMyData\Models\Response` περιέχει το αποτέλεσμα της αποστολής ενός παραστατικού.

Κάθε `Response` περιέχει ένα πεδίο `index` (***με αρχή το 1***) το οποίο αντιστοιχεί στη θέση του παραστατικού στον πίνακα παραστατικών που διαβιβάστηκαν.
Δηλαδή, αν έχουμε διαβιβάσει τρία παραστατικά, το πεδίο `index` του πρώτου `Response` θα είναι 1, του δεύτερου 2 και του τρίτου 3 και αυτά θα 
αντιστοιχούν στις θέσεις 0 για το πρώτο, 1 για το δεύτερο και 2 για τρίτο στον πίνακα των παραστατικών που διαβιβάστηκαν. 

Το πεδίο `status` περιέχει το αποτέλεσμα της αποστολής του παραστατικού. Αν το πεδίο `status` είναι `Success` τότε η αποστολή ήταν επιτυχής.
Διαφορετικά, στην περίπτωση αποτυχίας, η αποστολή θα περιέχει επίσης και το πεδίο `message` για το μήνυμα λάθους.

```php