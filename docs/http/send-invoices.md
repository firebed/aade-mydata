# Διαβίβαση παραστατικών - SendInvoices

```shell
# production
https://mydatapi.aade.gr/myDATA/SendInvoices

# development
https://mydataapidev.aade.gr/SendInvoices
```

## Αποστολή παραστατικών

Υπάρχουν διάφοροι τρόποι για τη διαβίβαση παραστατικών στο σύστημα του ΑΑΔΕ myDATA.
Για την αποστολή παραστατικών στο σύστημα του ΑΑΔΕ myDATA, χρησιμοποιήστε η κλάση `Firebed\AadeMyData\Http\SendInvoices`.
Η κλάση αυτή δέχεται ως παράμετρο:

- είτε ένα αντικείμενο `Firebed\AadeMyData\Models\InvoicesDoc`
- είτε ένα αντικείμενο `Firebed\AadeMyData\Models\Invoice`
- είτε έναν πίνακα από αντικείμενα `Firebed\AadeMyData\Models\Invoice`.

> [!NOTE]
> Στα παρακάτω παραδείγματα δημιουργούμε κενά αντικείμενα `Firebed\AadeMyData\Models\Invoice` για χάριν συντομίας. Κανονικά θα πρέπει να
> συμπληρώσουμε όλα τα απαραίτητα πεδία του παραστατικού πριν την αποστολή του.
>
> [**Δείτε περισσότερα**](../types/invoice-type)

### Αποστολή ενός μόνο παραστατικού

Η διαβίβαση ενός μόνο παραστατικού είναι μια από τις πιο συχνές περιπτώσεις χρήσης. Στο παρακάτω παράδειγμα, δημιουργούμε ένα αντικείμενο `Firebed\AadeMyData\Models\Invoice` και το διαβιβάζουμε στο σύστημα του ΑΑΔΕ myDATA.

```php
use Firebed\AadeMyData\Http\SendInvoices;
use Firebed\AadeMyData\Models\InvoicesDoc;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Exceptions\MyDataException;

$invoice = new Invoice();
$request = new SendInvoices();

try {
    $response = $request->handle($invoice);
} catch (MyDataException $e) {
    echo "Σφάλμα επικοινωνίας: " . $e->getMessage();
}
```

### Αποστολή πολλών παραστατικών με τη χρήση πίνακα αντικειμένων `Invoice`

Αρκετές φορές χρειαζόμαστε να διαβιβάσουμε πολλά παραστατικά ταυτόχρονα. Στο παρακάτω παράδειγμα,
δημιουργούμε τρία αντικείμενα `Firebed\AadeMyData\Models\Invoice` και τα διαβιβάζουμε ταυτόχρονα στο σύστημα του ΑΑΔΕ myDATA.

```php
$invoice1 = new Invoice();
$invoice2 = new Invoice();
$invoice3 = new Invoice();

$request = new SendInvoices();    
try {
    $response = $request->handle([$invoice1, $invoice2, $invoice3]);
} catch (MyDataException $e) {
    echo "Σφάλμα επικοινωνίας: " . $e->getMessage();
}
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

try {
    $response = $request->handle($doc);
} catch (MyDataException $e) {
    echo "Σφάλμα επικοινωνίας: " . $e->getMessage();
}
```

> [!NOTE]
> Στην περίπτωση που η αποστολή παραστατικών αποτύχει λόγω σφάλματος επικοινωνίας,
> η εξαίρεση `Firebed\AadeMyData\Exceptions\MyDataException` θα περιέχει το μήνυμα λάθους.
> Σε τέτοιες περιπτώσεις **κανένα παραστατικό** δε θα διαβιβαστεί στο σύστημα του ΑΑΔΕ myDATA.

## Λήψη αποτελεσμάτων

Το αποτέλεσμα της αποστολής παραστατικών είναι ένα αντικείμενο `Firebed\AadeMyData\Models\ResponseDoc` το οποίο περιέχει τόσα αντικείμενα
[**Firebed\AadeMyData\Models\Response**](../types/response-type) όσα και τα παραστατικά που διαβιβάστηκαν. Κάθε αντικείμενο `Firebed\AadeMyData\Models\Response` περιέχει το αποτέλεσμα της αποστολής ενός παραστατικού.

Κάθε `Response` περιέχει ένα πεδίο `index` (***με αρχή το 1***) το οποίο αντιστοιχεί στη θέση του παραστατικού στον πίνακα παραστατικών που διαβιβάστηκαν.
Δηλαδή, αν έχουμε διαβιβάσει τρία παραστατικά, το πεδίο `index` του πρώτου `Response` θα είναι 1, του δεύτερου 2 και του τρίτου 3 και αυτά θα
αντιστοιχούν στις θέσεις 0 για το πρώτο, 1 για το δεύτερο και 2 για τρίτο στον πίνακα των παραστατικών που διαβιβάστηκαν.

Το πεδίο `status` περιέχει το αποτέλεσμα της αποστολής του παραστατικού. Αν το πεδίο `status` είναι `Success` τότε η αποστολή ήταν επιτυχής.
Διαφορετικά, στην περίπτωση αποτυχίας, η αποστολή θα περιέχει επίσης και το πεδίο `message` για το μήνυμα λάθους.

```php
use Firebed\AadeMyData\Http\SendInvoices;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Exceptions\MyDataException;

// Create the request and send 2 invoices
$invoicesToBeSent = [new Invoice(), new Invoice()];
$sendInvoices = new SendInvoices();

try {
    $responses = $sendInvoices->handle($invoicesToBeSent);
    
    $errors = [];
    foreach ($responses as $response) {
        if ($response->isSuccessful()) { // $response->getStatusCode() === 'Success';   
            // This invoice was successfully registered.     
            // Each response has an index value which corresponds
            // to the index (-1) of the $invoicesToBeSent array.
            
            $index = $response->getIndex(); // $sentInvoice = $invoicesToBeSent[$index - 1];
            $uid = $response->getInvoiceUid();
            $mark = $response->getInvoiceMark();
            $cancelledByMark = $response->getCancellationMark();
            $qrUrl = $response->getQrUrl();
    
            // Typically, you should have an invoice object of your
            // own and an invoice reference from myDATA, and you
            // will have to relate these together. 
            // Retrieve the invoice's uid, mark, qr and other values
            // from the response, then establish the correlation
            // with your local invoice and persist these details in your database.
            print_r(compact('index', 'uid', 'mark', 'cancelledByMark', 'qrUrl'));
        } else {
            // There were some errors for this specific invoice. See errors for details.
            foreach ($response->getErrors() as $error) {
                $errors[$response->getIndex()][] = $error->getCode() . ': ' . $error->getMessage();
            }
        }
    }
} catch (MyDataException $e) {
    // There was a communication error. None of the invoices were sent.
    echo "Σφάλμα επικοινωνίας: " . $e->getMessage();
}
```

> [!CAUTION]
> **Στην περίπτωση διαβίβασης πολλαπλών παραστατικών ταυτόχρονα, θα πρέπει να ελέγξουμε το αποτέλεσμα της αποστολής κάθε παραστατικού ξεχωριστά.
> Υπάρχει περίπτωση να έχουμε επιτυχία σε ένα παραστατικό και αποτυχία σε ένα άλλο.**