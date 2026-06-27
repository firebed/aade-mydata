# Ακύρωση Δελτίου Ποσοτικής Παραλαβής - CancelReceivingNote

```shell
# production
https://mydatapi.aade.gr/myDataProvider/CancelReceivingNote?mark={mark}&entityVatNumber={entityVatNumber}

# development
https://mydataapidev.aade.gr/myDATAProvider/CancelReceivingNote?mark={mark}&entityVatNumber={entityVatNumber}
```

> [!WARNING]
> Η ακύρωση Δελτίων Ποσοτικής Παραλαβής (τύπου 10.1 / 10.2) υποστηρίζεται μόνο για παρόχους ηλεκτρονικής τιμολόγησης.

Για την ακύρωση ενός Δελτίου Ποσοτικής Παραλαβής στο σύστημα του ΑΑΔΕ myDATA, χρησιμοποιήστε την κλάση `Firebed\AadeMyData\Http\CancelReceivingNote`.
Η ακύρωση γίνεται παρέχοντας το mark του δελτίου που θέλουμε να ακυρώσουμε και το ΑΦΜ του εκδότη.

```php
use Firebed\AadeMyData\Http\CancelReceivingNote;
use Firebed\AadeMyData\Exceptions\MyDataException;

$cancel = new CancelReceivingNote();

try{
    $responses = $cancel->handle("1234567890", "888888888");
} catch (MyDataException $e) {
    echo "Σφάλμα επικοινωνίας: " . $e->getMessage();
}
```

## Λήψη αποτελεσμάτων
Σε περίπτωση επιτυχίας η ακύρωση ως πράξη λαμβάνει το δικό της mark το οποίο
επιστρέφεται στον χρήστη και το δελτίο θεωρείται ακυρωμένο. Σε περίπτωση
αποτυχίας επιστρέφεται το αντίστοιχο μήνυμα λάθους.

> [!TIP]
> Το αποτέλεσμα της κλήσης επιστρέφει έναν πίνακα από αντικείμενα τύπου
> [**\Firebed\AadeMyData\Models\Response**](../types/response-type).

```php
use Firebed\AadeMyData\Http\CancelReceivingNote;
use Firebed\AadeMyData\Exceptions\MyDataException;

try {
    $cancel = new CancelReceivingNote();
    $responses = $cancel->handle("1234567890", "888888888");

    $response = $responses->first();

    if ($response->isSuccessful()) {
        echo "Το δελτίο ακυρώθηκε με επιτυχία." . PHP_EOL;
        echo "Το mark ακύρωσης του δελτίου είναι: " . $response->getCancellationMark();
    } else {
        echo "Η ακύρωση απέτυχε. Λόγος: " . $response->getMessage();
    }
} catch (MyDataException $e) {
    echo "Σφάλμα επικοινωνίας: " . $e->getMessage();
}
```
