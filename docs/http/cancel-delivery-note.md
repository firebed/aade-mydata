# Ακύρωση δελτίου αποστολής - CancelDeliveryNote

```shell
# production
https://mydatapi.aade.gr/myDataProvider/CancelDeliveryNote?mark={mark}&entityVatNumber={entityVatNumber}

# development
https://mydataapidev.aade.gr/myDATAProvider/CancelDeliveryNote?mark={mark}&entityVatNumber={entityVatNumber}
```

> [!WARNING]
> Η ακύρωση δελτίων αποστολής υποστηρίζεται μόνο για παρόχους ηλεκτρονικής τιμολόγησης.

Για την ακύρωση ενός δελτίου αποστολής στο σύστημα του ΑΑΔΕ myDATA, χρησιμοποιήστε την κλάση `Firebed\AadeMyData\Http\CancelDeliveryNote`.
Η ακύρωση δελτίου αποστολής γίνεται παρέχοντας το mark του δελτίου αποστολής που θέλουμε να ακυρώσουμε και το ΑΦΜ του εκδότη.

```php
use Firebed\AadeMyData\Http\CancelDeliveryNote;
use Firebed\AadeMyData\Exceptions\MyDataException;

$cancel = new CancelDeliveryNote();

try{
    $responses = $cancel->handle("1234567890", "888888888");
} catch (MyDataException $e) {
    echo "Σφάλμα επικοινωνίας: " . $e->getMessage();
}
```

## Λήψη αποτελεσμάτων
Σε περίπτωση επιτυχίας η ακύρωση ως πράξη λαμβάνει το δικό της mark το οποίο
επιστρέφεται στον χρήστη και το δελτίο αποστολής θεωρείται ακυρωμένο. Σε περίπτωση
αποτυχίας επιστρέφεται το αντίστοιχο μήνυμα λάθους.

> [!TIP]
> Το αποτέλεσμα της κλήσης επιστρέφει έναν πίνακα από αντικείμενα τύπου
> [**\Firebed\AadeMyData\Models\Response**](../types/response-type).

```php
use Firebed\AadeMyData\Http\CancelDeliveryNote;
use Firebed\AadeMyData\Exceptions\MyDataException;

try {
    $cancel = new CancelDeliveryNote();
    $responses = $cancel->handle("1234567890", "888888888");
    
    $response = $responses->first();
    
    if ($response->isSuccessful()) {
        echo "Το δελτίο αποστολής ακυρώθηκε με επιτυχία." . PHP_EOL;
        echo "Το mark ακύρωσης του δελτίου αποστολής είναι: " . $response->getCancellationMark();
    } else {
        echo "Η ακύρωση απέτυχε. Λόγος: " . $response->getMessage();
    }
} catch (MyDataException $e) {
    echo "Σφάλμα επικοινωνίας: " . $e->getMessage();
}
```