# Λήψη εσόδων - RequestMyIncome

```shell
# production
https://mydatapi.aade.gr/myDATA/RequestMyIncome

# development
https://mydataapidev.aade.gr/RequestMyIncome
```

Με αυτή τη μέθοδο ο χρήστης λαμβάνει πληροφορίες σχετικά με τα έσοδα του για ένα
συγκεκριμένο χρονικό διάστημα. Η κλήση επιστρέφει γραμμές με πληροφορίες για τα έσοδα 
του χρήστη, για συγκεκριμένο ημερολογιακό κλειστό διάστημα που ορίζεται από τις τιμές
των παραμέτρων `dateFrom` και `dateTo`. Προαιρετικά η αναζήτηση μπορεί να πραγματοποιηθεί
με επιπλέον φίλτρα συγκεκριμένο ΑΦΜ αντισυμβαλλόμενου και συγκεκριμένο τύπο παραστατικού.

## Παράμετροι

| Όνομα Παραμέτρου | Υποχρεωτικό | Περιγραφή                                           |
|------------------|-------------|-----------------------------------------------------|
| dateFrom         | Ναι         | Ημερομηνία από                                      |
| dateTo           | Ναι         | Ημερομηνία έως                                      |
| counterVatNumber | Όχι         | ΑΦΜ αντισυμβαλλόμενου                               |
| entityVatNumber  | Όχι         | ΑΦΜ αναφοράς                                        |
| invType          | Όχι         | Τύπος παραστατικού                                  |
| nextPartitionKey | Όχι         | Παράμετρος για την τμηματική λήψη των αποτελεσμάτων |
| nextRowKey       | Όχι         | Παράμετρος για την τμηματική λήψη των αποτελεσμάτων |

> [!TIP]
> Το αποτέλεσμα της κλήσης επιστρέφει έναν πίνακα από αντικείμενα τύπου 
> [**\Firebed\AadeMyData\Models\BookInfo**](../types/book-info-type).

## Παρατηρήσεις

- Αν η παράμετρος `entityVatNumber` έχει τιμή, η αναζήτηση θα πραγματοποιηθεί για
  αυτόν τον ΑΦΜ, αλλιώς για τον ΑΦΜ του χρήστη που καλεί τη μέθοδο.
- Οι παράμετροι ημερομηνιών πρέπει να εισαχθούν με format dd/MM/yyyy.
- Όταν μια προαιρετική παράμετρος δεν εισάγεται, η αναζήτηση πραγματοποιείται
  για όλες τις πιθανές τιμές που θα μπορούσε να έχει αυτό το πεδίο.
- Στην περίπτωση που τα αποτελέσματα αναζήτησης ξεπερνούν σε μέγεθος το
  μέγιστο επιτρεπτό όριο ο χρήστης θα τα λάβει τμηματικά. Τα πεδία
  `nextPartitionKey` και `nextRowKey` θα εμπεριέχονται σε κάθε τμήμα των
  αποτελεσμάτων και θα χρησιμοποιούνται ως παράμετροι στην κλήση για τη λήψη
  του επόμενου τμήματος αποτελεσμάτων.

## Παραδείγματα

```php
use Firebed\AadeMyData\Http\RequestMyIncome;
use Firebed\AadeMyData\Exceptions\MyDataException;

try {
    $request = new RequestMyIncome();
    $response = $request->handle(
        dateFrom: '01/01/2021',
        dateTo: '31/12/2021'
    );
    
    // Για την κλήση του επόμενου τμήματος αποτελεσμάτων
    $continuationToken = $response->getContinuationToken();
    
    // Λήψη των πληροφοριών του βιβλίου εσόδων
    $bookInfoArray = $response->getBookInfo();
    
    foreach ($bookInfoArray as $bookInfo) {
        echo $bookInfo->getCounterVatNumber() . PHP_EOL;
        echo $bookInfo->getIssueDate() . PHP_EOL;        
        echo $bookInfo->getNetValue() . PHP_EOL;
        // ...        
    }
} catch (MyDataException $e) {
    echo $e->getMessage();
}
```