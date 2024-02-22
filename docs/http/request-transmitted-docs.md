# Λήψη εκδοθέντων παραστατικών - RequestTransmittedDocs

```shell
# production
https://mydatapi.aade.gr/myDATA/RequestTransmittedDocs

# development
https://mydataapidev.aade.gr/RequestTransmittedDocs
```

Με αυτή τη μέθοδο ο χρήστης λαμβάνει παραστατικά, χαρακτηρισμούς, ακυρώσεις
παραστατικών **που έχει υποβάλλει ο ίδιος** και τον αφορούν.

Για την περίπτωση εκείνη και μόνο που η μέθοδος κληθεί από τρίτο
πρόσωπο (όπως εκπρόσωπος Ν.Π. ή λογιστής), ο ΑΦΜ της οντότητας για τον οποίο
πραγματοποιείται η αναζήτηση αποστέλλεται μέσω της παραμέτρου entityVatNumber,
διαφορετικά η εν λόγω παράμετρος δε χρειάζεται να αποσταλεί.

## Παράμετροι

| Όνομα Παραμέτρου  | Υποχρεωτικό | Περιγραφή                                             |
|-------------------|-------------|-------------------------------------------------------|
| mark              | Ναι         | Μοναδικός αριθμός καταχώρησης                         |
| entityVatNumber   | Όχι         | ΑΦΜ οντότητας                                         |
| dateFrom          | Όχι         | Αρχή χρονικού διαστήματος αναζήτησης για την έκδοσης  |
| dateTo            | Όχι         | Τέλος χρονικού διαστήματος αναζήτησης για την έκδοσης |
| receiverVatNumber | Όχι         | ΑΦΜ αντισυμβαλλόμενου                                 |
| invType           | Όχι         | Τύπος παραστατικού                                    |
| maxMark           | Όχι         | Μέγιστος Αριθμός ΜΑΡΚ                                 |
| nextPartitionKey  | Όχι         | Παράμετρος για την τμηματική λήψη των αποτελεσμάτων   |
| nextRowKey        | Όχι         | Παράμετρος για την τμηματική λήψη των αποτελεσμάτων   |

> [!TIP]
> Το αποτέλεσμα της κλήσης επιστρέφει έναν πίνακα από αντικείμενα τύπου
> [**\Firebed\AadeMyData\Models\RequestedDoc**](../types/requested-doc-type).
> 
Η κλήση επιστρέφει όσα στοιχεία αφορούν τον χρήστη και έχουν ως αναγνωριστικό
Μοναδικό Αριθμό Καταχώρησης μεγαλύτερο της παραμέτρου.

### Παρατηρήσεις

- Στην περίπτωση που τα αποτελέσματα αναζήτησης ξεπερνούν σε μέγεθος το
  μέγιστο επιτρεπτό όριο ο χρήστης θα τα λάβει τμηματικά. Τα πεδία
  `nextPartitionKey` και `nextRowKey` θα εμπεριέχονται σε κάθε τμήμα των
  αποτελεσμάτων και θα χρησιμοποιούνται ως παράμετροι στην κλήση για τη λήψη
  του επόμενου τμήματος αποτελεσμάτων.
- Σε περίπτωση που κάποια εκ των παραπάνω παραμέτρων δεν έχει τιμή, η
  αναζήτηση πραγματοποιείται για όλες τις πιθανές τιμές αυτού του πεδίου, όπως
  προηγουμένως.
- Σε περίπτωση που μόνο μια εκ των `dateFrom`, `dateTo` παραληφθεί, η αναζήτηση θα
  εκτελεστεί μόνο για την ημερομηνία που έχει δοθεί στην άλλη παράμετρο. Αν και οι
  παράμετροι έχουν τιμή, η αναζήτηση θα εκτελεστεί για το διάστημα από dateFrom
  έως dateTo.
- Εφόσον αποδοθεί τιμή στην παράμετρο maxMark, θα επιστραφούν όσες εγγραφές
  έχουν ΜΑΡΚ μικρότερο ή ίσο αυτή της τιμής.
- Οι τιμές των παραμέτρων `receiverVatNumber` και `invType` εφαρμόζονται πάντα με
  τον συντελεστή ισότητας (equal operator).
- Στην παράμετρο invType δίνεται ως τιμή ο αριθμός που αντιστοιχεί στον
  συγκεκριμένο τύπο σύμφωνα με τον [πίνακα Παραρτήματος](../appendix/invoice-types).

### Παράδειγμα κλήσης χωρίς `continuationToken`

```php
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\RequestTransmittedDocs;

$request = new RequestTransmittedDocs();

try {
  $response = $request->handle(
      mark: "1234567890",
      dateFrom: "01/01/2021",
      dateTo: "31/12/2021",
      receiverVatNumber: "123456789",
      entityVatNumber: "123456789",
      invType: InvoiceType::TYPE_1_1,
  );
} catch (MyDataException $e) {
    echo "Σφάλμα επικοινωνίας: " . $e->getMessage();
}
```

### Παράδειγμα κλήσης με `continuationToken`

```php
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Exceptions\MyDataException;
use Firebed\AadeMyData\Http\RequestTransmittedDocs;

try {
  // Λήψη πρώτης σελίδας αποτελεσμάτων
  $request1 = new RequestTransmittedDocs();
  $response1 = $request1->handle("1234567890");
  
  $continuationToken = $response1->getContinuationToken();
  
  // Λήψη δεύτερης σελίδας αποτελεσμάτων
  $request2 = new RequestTransmittedDocs();
  $response2 = $request->handle(
      mark: "1234567890",
      dateFrom: "01/01/2021",
      dateTo: "31/12/2021",
      receiverVatNumber: "123456789",
      entityVatNumber: "123456789",
      invType: InvoiceType::TYPE_1_1,
      nextPartitionKey: $continuationToken->getNextPartitionKey(),
      nextRowKey: $continuationToken->getNextRowKey(),
  );
} catch (MyDataException $e) {
    echo "Σφάλμα επικοινωνίας: " . $e->getMessage();
}
```





