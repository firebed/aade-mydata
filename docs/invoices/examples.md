# Παραδείγματα Παραστατικών

Σε όλα τα ακόλουθα παραδείγματα θα χρησιμοποιήσουμε το ΑΦΜ `888888888` για τον εκδότη του
παραστατικού και το ΑΦΜ `999999999` για τον λήπτη του παραστατικού.

> [!CAUTION]
> Κατά τη διαβίβαση των παραστατικών σας στο myDATA, είτε είστε στο παραγωγικό είτε
> στο δοκιμαστικό περιβάλλον, θα πρέπει να χρησιμοποιήσετε **έγκυρα ΑΦΜ** εκδότη,
> λήπτη ή άλλων οντοτήτων.

Συνιστάται πάντα να ελέγχετε τα παραδείγματα πριν την πραγματική χρήση τους. Η αποστολή των
παραστατικών γίνεται με την κλάση `Firebed\AadeMyData\Http\SendInvoices`. Ύστερα
από την αποστολή, θα έχετε στη διάθεση σας:

- ένα πίνακα αντικειμένων `Firebed\AadeMyData\Models\Response`,
- το XML που χρησιμοποιήθηκε για την αποστολή,
- και το XML που επέστρεψε το myDATA.

## Αποθήκευση των XML δεδομένων

Το σύστημα παράγει ένα XML για την αίτηση αποστολής και διαθέτει το XML που επιστρέφει το myDATA.
Η μορφή του XML είναι θα είναι τύπου `DOMDocument`. Αυτό μας δίνει την ευελιξία να διαχειριστούμε
τα XML όπως επιθυμούμε.

> [!TIP]
> **Στο παραγωγικό περιβάλλον είναι σημαντικό να αποθηκεύσετε τα παραγόμενα XML, είτε σε αρχεία είτε στη βάση
> δεδομένων σας, για τυχόν αποσφαλμάτωση.**

### Αντιστοίχιση του XML με ένα παραστατικό

Όταν πρόκειται για αποστολή ενός μόνο παραστατικού, είναι εύκολο να αντιστοιχίσετε το XML με το παραστατικό,
καθώς όλο τα XML αφορούν μόνο το παραστατικό που αποστέλλεται.

```php
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\SendInvoices;
use Firebed\AadeMyData\Models\Invoice;

try {
    $invoice = new Invoice();
    
    $request = new SendInvoices();
    $responses = $request->handle($invoice);
        
    // Ανάκτηση του XML αίτησης
    $requestDom = $request->getRequestDom();
    $requestXml = $requestDom->saveXML();
        
    // Ανάκτηση του XML απάντησης
    $responseDom = $request->getRequestDom();
    $responseXml = $responseDom->saveXML();
    
    // Ανάκτηση και εξέταση της πρώτης απάντησης
    $response = $responses->first();    
    if ($response->isSuccessful()) {
        $uid = $response->getInvoiceUid();
        $mark = $response->getInvoiceMark();
        $qrUrl = $response->getQrUrl();
        
        // Αντιστοίχηση και αποθήκευση των XML στη βάση δεδομένων
        // INSERT INTO invoices (..., uid, mark, qr_url, request_xml, response_xml)
    } else {
        // Το παραστατικό δεν καταχωρήθηκε, αν επιθυμείτε μπορείτε
        // να αποθηκεύσετε τα σφάλματα στη βάση δεδομένων για ανάλυση
        $errors = $response->getErrors();
        
        // INSERT INTO logs (..., request_xml, response_xml)
    }
} catch (MyDataException $e) {
    echo "Σφάλμα επικοινωνίας: " . $e->getMessage();
}
```

### Αντιστοίχιση του XML με πολλά παραστατικά

Στις περιπτώσεις που γίνεται ταυτόχρονη αποστολή πολλών παραστατικών, το myDATA επιστρέφει
**ένα XML** που περιέχει τις απαντήσεις **για όλα τα παραστατικά**. Σε αυτή την περίπτωση, δε θα ήταν 
σωστό να αντιστοιχίσουμε ένα παραστατικό με το σύνολο των απαντήσεων.

Για τη σωστή αντιστοίχηση των παραστατικών με το XML αιτήματος και με το XML απάντησης θα πρέπει
να εξάγουμε από τα αντίστοιχα XML τα σχετικά μέρη που αντιστοιχούν σε κάθε παραστατικό. 
Αυτό μπορεί να γίνει σχετικά εύκολα όπως φαίνεται στο παρακάτω παράδειγμα.

```php
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\SendInvoices;
use Firebed\AadeMyData\Models\Invoice;

try {
    // Αρχικοποίηση τριών (3) παραστατικών
    $invoicesArray = [new Invoice(), new Invoice(), new Invoice()
    
    // Αποστολή των παραστατικών
    $request = new SendInvoices();
    $responses = $request->handle($invoicesArray);
        
    // Ανάκτηση του XML αίτησης
    $requestDom = $request->getRequestDom();
        
    // Ανάκτηση του XML απάντησης
    // Η απάντηση περιέχει τα αποτελέσματα και για τα 3 παραστατικά
    $responseDom = $request->getRequestDom();
        
    foreach ($responses as $response) {
        $index = $response->getIndex() - 1;
        
        // Ανάκτηση του σχετικού παραστατικού
        $relatedInvoice = $invoicesArray[$index];
        
        // Ανάκτηση του σχετικού XML αιτήματος με χρήση του index
        $relatedRequestDom = $requestDom->getElementsByTagName('invoice')->item($index);
        $relatedRequestXml = $requestDom->saveXML($relatedRequestDom);
        
        // Ανάκτηση του σχετικού XML απάντησης με χρήση του index
        $relatedResponseDom = $responseDom->getElementsByTagName('response')->item($index);
        $relatedResponseXml = $responseDom->saveXML($relatedResponseDom);
            
        // Εξέταση της απάντησης
        if ($response->isSuccessful()) {
            $uid = $response->getInvoiceUid();
            $mark = $response->getInvoiceMark();
            $qrUrl = $response->getQrUrl();
            
            // Αντιστοίχηση και αποθήκευση των XML στη βάση δεδομένων
            // INSERT INTO invoices (..., uid, mark, qr_url, related_request_xml, related_response_xml)
        } else {
            // Το παραστατικό δεν καταχωρήθηκε, αν επιθυμείτε μπορείτε
            // να αποθηκεύσετε τα σφάλματα στη βάση δεδομένων για ανάλυση
            $errors = $response->getErrors()->toArray();
            
            // INSERT INTO logs (..., errors, related_request_xml, related_response_xml)            
        }
    }
} catch (MyDataException $e) {
    echo "Σφάλμα επικοινωνίας: " . $e->getMessage();
}
```