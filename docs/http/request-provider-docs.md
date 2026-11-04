# Λήψη διαβιβασθέντων παραστατικών παρόχου - RequestProviderDocs

```shell
# production
https://mydatapi.aade.gr/myDataProvider/RequestTransmittedDocs?issuervat={issuervat}&mark={mark}

# development
https://mydataapidev.aade.gr/myDATAProvider/RequestTransmittedDocs?issuervat={issuervat}&mark={mark}
```

> [!WARNING]
> Μόνο για παρόχους ηλεκτρονικής τιμολόγησης. Η `RequestTransmittedDocs` του ERP καναλιού (`Firebed\AadeMyData\Http\RequestTransmittedDocs`)
> δεν δέχεται πλέον σύνδεση παρόχου και πετά `UnsupportedChannelException`.

Ο πάροχος δεν έχει πρόσβαση στα παραστατικά της επιχείρησης (θα χρειαζόταν τα δικά της διαπιστευτήρια myDATA). Με την
`Firebed\AadeMyData\Http\RequestProviderDocs` λαμβάνει, για έναν εκδότη, τη σύνοψη όσων παραστατικών έχει διαβιβάσει ο
ίδιος με ΜΑΡΚ μεγαλύτερο της παραμέτρου: ΑΦΜ εκδότη, ΜΑΡΚ, UID και authentication code.

```php
use Firebed\AadeMyData\MyData;

$mydata = MyData::connect($user_id, $subscription_key, 'prod', provider: true);

$doc = $mydata->requestProviderDocs(issuerVat: '123456789', mark: $lastKnownMark);

foreach ($doc->getInvoices() as $invoice) {
    $invoice->getIssuerVAT();           // ΑΦΜ εκδότη
    $invoice->getInvoiceProviderMark(); // ΜΑΡΚ (int)
    $invoice->getInvoiceUid();
    $invoice->getAuthenticationCode();
}

// Όλα τα ΜΑΡΚ ενός UID· περισσότερα από ένα σημαίνει ότι το myDATA κατέγραψε το παραστατικό δύο φορές
$doc->findByUid($uid);
```

Για όλες τις σελίδες (continuation token):

```php
use Firebed\AadeMyData\Http\RequestProviderDocs;

foreach ($mydata->request(new RequestProviderDocs)->invoices('123456789', $lastKnownMark) as $invoice) {
    // κάθε ProviderInvoice κάθε σελίδας
}
```

> [!IMPORTANT]
> Το myDATA αποθηκεύει τις υποβολές από ουρά. Ένα παραστατικό εμφανίζεται εδώ μόνο αφού αποθηκευτεί, κάτι που στο
> παραγωγικό περιβάλλον μπορεί να πάρει ώρες μετά την απάντηση της `SendInvoices`. Η απουσία ενός παραστατικού δεν
> σημαίνει ότι δεν καταχωρήθηκε.
