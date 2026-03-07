# Ψηφιακή Διακίνηση Αγαθών - Digital Goods Movement

Το σύστημα Digital Goods Movement του ΑΑΔΕ myDATA επιτρέπει την παρακολούθηση και διαχείριση της διακίνησης αγαθών σε πραγματικό χρόνο. Οι λειτουργίες αυτές είναι διαθέσιμες **μόνο για το ERP channel** και **δεν** υποστηρίζονται από παρόχους ηλεκτρονικής τιμολόγησης.

> [!WARNING]
> Όλες οι λειτουργίες Digital Goods Movement υποστηρίζονται μόνο για τον εκδότη (ERP channel).

## RegisterTransfer - Καταχώρηση Μεταφοράς

```shell
# production
https://mydatapi.aade.gr/myDATA/RegisterTransfer

# development
https://mydataapidev.aade.gr/RegisterTransfer
```

Καταχωρεί τα στοιχεία μεταφοράς για ένα δελτίο αποστολής που βρίσκεται σε διακίνηση. Η μέθοδος καλείται από τον μεταφορέα για να δηλώσει την παραλαβή των αγαθών και την έναρξη της διακίνησης, ή την παραλαβή από προηγούμενο μεταφορέα (μεταφόρτωση).

### Παράδειγμα χρήσης

```php
use Firebed\AadeMyData\Http\DigitalGoodsMovement\RegisterTransfer;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\Transport;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\TransportDetails;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\Location;
use Firebed\AadeMyData\Enums\DigitalGoodsMovement\TransportType;
use Firebed\AadeMyData\Exceptions\MyDataException;

// Δημιουργία στοιχείων μεταφοράς
$details = new TransportDetails();
$details->setVehicleNumber('AHN0011');
$details->setTransportType(TransportType::PRIVATE_USE_TRUCK);
$details->setCarrierVatNumber('777777777');
$details->setTrailorNumber('P22345'); // Αριθμός κυκλοφορίας "P" (προαιρετικό)
$details->setLocation(new Location(41.303921, -81.901693)); // latitude, longitude

$transport = new Transport();
$transport->setQrUrl('https://mydataapidev.aade.gr/TimologioQR/QRInfo?q=test_qr_url');
$transport->setTransportDetail($details);

$registerTransfer = new RegisterTransfer();

try {
    $response = $registerTransfer->handle($transport);

    if ($response->first()->isSuccessful()) {
        echo "Η μεταφορά καταχωρήθηκε με επιτυχία." . PHP_EOL;
        echo "Transfer Mark: " . $response->first()->getTransferMark();
    }
} catch (MyDataException $e) {
    echo "Σφάλμα επικοινωνίας: " . $e->getMessage();
}
```

### Πεδία TransportDetails

| Πεδίο              | Τύπος         | Υποχρεωτικό | Περιγραφή                                                  |
|--------------------|---------------|-------------|------------------------------------------------------------|
| `vehicleNumber`    | string        | Ναι         | Αριθμός Μεταφορικού Μέσου (max 50 χαρακτήρες)              |
| `transportType`    | TransportType | Ναι         | Είδος Μεταφορικού Μέσου (1-7)                              |
| `carrierVatNumber` | string        | Ναι         | ΑΦΜ Μεταφορικής Εταιρείας (max 20 χαρακτήρες)              |
| `pNumber`          | string        | Όχι         | Αριθμός κυκλοφορίας "P" ρυμουλκούμενου (max 50 χαρακτήρες) |
| `location`         | Location      | Όχι         | Τοποθεσία Μεταφόρτωσης (longitude, latitude)               |

### Τύποι Μεταφορικού Μέσου (TransportType)

- `PUBLIC_USE_TRUCK` (1) - Φορτηγό Δημόσιας Χρήσης
- `PRIVATE_USE_TRUCK` (2) - Φορτηγό Ιδιωτικής Χρήσης
- `SHIP` (3) - Πλοίο
- `TRAIN` (4) - Τρένο
- `AIRPLANE` (5) - Αεροπλάνο
- `OTHER_TRANSPORT_MEANS` (6) - Λοιπά Μεταφορικά Μέσα
- `WITHOUT` (7) - Άνευ

## GenerateGroupQrCode - Δημιουργία Ομαδικού QR Code

```shell
# production
https://mydatapi.aade.gr/myDATA/GenerateGroupQRCode

# development
https://mydataapidev.aade.gr/GenerateGroupQRCode
```

Δημιουργεί ένα ομαδικό QR code που συνδυάζει πολλαπλά QR codes από διαφορετικά δελτία αποστολής. Απαιτούνται τουλάχιστον 2 QR URLs.

### Παράδειγμα χρήσης

```php
use Firebed\AadeMyData\Http\DigitalGoodsMovement\GenerateGroupQrCode;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\GroupQrCode;
use Firebed\AadeMyData\Exceptions\MyDataException;

$groupQrCode = new GroupQrCode();
$groupQrCode->addQrUrl("https://mydataapidev.aade.gr/TimologioQR/QRInfo?q=test_url_1");
$groupQrCode->addQrUrl("https://mydataapidev.aade.gr/TimologioQR/QRInfo?q=test_url_2");

$generate = new GenerateGroupQrCode();

try {
    $response = $generate->handle($groupQrCode);

    echo "Group QR URL: " . $response->getGroupQrUrl() . PHP_EOL;
    echo "Group ID: " . $response->getGroupId() . PHP_EOL;
    echo "QR URLs Count: " . $response->getQrUrlsCount() . PHP_EOL;
    echo "Created at: " . $response->getCreatedAt() . PHP_EOL;
    echo "Expires at: " . $response->getExpiresAt() . PHP_EOL;
} catch (MyDataException $e) {
    echo "Σφάλμα επικοινωνίας: " . $e->getMessage();
}
```

### Helper method

Εναλλακτικά, μπορείτε να χρησιμοποιήσετε τη μέθοδο `generateFromQrUrls`:

```php
$qrUrls = [
    "https://mydataapidev.aade.gr/TimologioQR/QRInfo?q=test_url_1",
    "https://mydataapidev.aade.gr/TimologioQR/QRInfo?q=test_url_2",
];

$generate = new GenerateGroupQrCode();
$response = $generate->generateFromQrUrls($qrUrls);
```

## ConfirmDeliveryOutcome - Επιβεβαίωση Παράδοσης

```shell
# production
https://mydatapi.aade.gr/myDATA/ConfirmDeliveryOutcome

# development
https://mydataapidev.aade.gr/ConfirmDeliveryOutcome
```

Επιβεβαιώνει την παράδοση αγαθών και καταχωρεί τα στοιχεία παράδοσης. Μπορεί να δηλώσει πλήρη, μερική ή καμία παράδοση.

### Παράδειγμα χρήσης

```php
use Firebed\AadeMyData\Http\DigitalGoodsMovement\ConfirmDeliveryOutcome;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\DeliveryOutcome;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\PackagingDetail;
use Firebed\AadeMyData\Enums\DigitalGoodsMovement\DeliveryOutcomeType;
use Firebed\AadeMyData\Enums\DigitalGoodsMovement\PackagingType;
use Firebed\AadeMyData\Exceptions\MyDataException;

$outcome = new DeliveryOutcome();
$outcome->setQrUrl('https://mydataapidev.aade.gr/TimologioQR/QRInfo?q=test_url');
$outcome->setOutcome(DeliveryOutcomeType::FULL);

// Προαιρετικά: Δήλωση συσκευασιών που παραδόθηκαν
$outcome->addDeliveredPackaging(new PackagingDetail(PackagingType::CRATE, 10));
$outcome->addDeliveredPackaging(new PackagingDetail(PackagingType::BOX, 2));

// Προαιρετικά: Ένδειξη παράδοσης χωρίς παρουσία παραλήπτη
$outcome->setDeliveredWithoutRecipient(false);

$confirm = new ConfirmDeliveryOutcome();

try {
    $response = $confirm->handle($outcome);

    if ($response->first()->isSuccessful()) {
        echo "Η παράδοση επιβεβαιώθηκε με επιτυχία." . PHP_EOL;
        echo "Delivery Outcome Mark: " . $response->first()->getDeliveryOutcomeMark();
    }
} catch (MyDataException $e) {
    echo "Σφάλμα επικοινωνίας: " . $e->getMessage();
}
```

### Πεδία DeliveryOutcome

| Πεδίο                       | Τύπος               | Υποχρεωτικό | Περιγραφή                                |
|-----------------------------|---------------------|-------------|------------------------------------------|
| `qrUrl`                     | string              | Ναι         | Το URL του QR code του Δελτίου           |
| `outcome`                   | DeliveryOutcomeType | Ναι         | Αποτέλεσμα παράδοσης (FULL/PARTIAL/NONE) |
| `deliveredWithoutRecipient` | boolean             | Όχι         | Παράδοση χωρίς φυσική παρουσία παραλήπτη |
| `deliveredPackaging`        | PackagingDetail[]   | Όχι         | Λίστα με συσκευασίες που παραδόθηκαν     |

### Τύποι Αποτελέσματος Παράδοσης (DeliveryOutcomeType)

- `FULL` - Πλήρης Παράδοση
- `PARTIAL` - Μερική Παράδοση
- `NONE` - Καμία Παράδοση

### Τύποι Συσκευασίας (PackagingType)

- `PALLET` (1) - Παλέτα
- `BOX` (2) - Κούτα
- `CRATE` (3) - Κιβώτιο
- `BARREL` (4) - Βαρέλι
- `SACK` (5) - Σάκος
- `OTHER` (6) - Λοιπά (απαιτεί `otherPackagingTypeTitle`)

## RejectDeliveryNote - Απόρριψη Δελτίου Αποστολής

```shell
# production
https://mydatapi.aade.gr/myDATA/RejectDeliveryNote

# development
https://mydataapidev.aade.gr/RejectDeliveryNote
```

Απορρίπτει ένα δελτίο αποστολής με συγκεκριμένο λόγο απόρριψης. Μπορεί να γίνει με χρήση mark ή QR URL.

### Απόρριψη με χρήση mark

```php
use Firebed\AadeMyData\Http\DigitalGoodsMovement\RejectDeliveryNote;
use Firebed\AadeMyData\Models\DigitalGoodsMovement\DeliveryRejection;
use Firebed\AadeMyData\Exceptions\MyDataException;

$rejection = new DeliveryRejection(111111111111111, 'Damaged goods');

$reject = new RejectDeliveryNote();

try {
    $response = $reject->handle($rejection);

    if ($response->first()->isSuccessful()) {
        echo "Το δελτίο απορρίφθηκε με επιτυχία." . PHP_EOL;
        echo "Reject Mark: " . $response->first()->getRejectMark();
    }
} catch (MyDataException $e) {
    echo "Σφάλμα επικοινωνίας: " . $e->getMessage();
}
```

### Απόρριψη με χρήση QR URL

```php
$rejection = new DeliveryRejection(
    'https://mydataapidev.aade.gr/TimologioQR/QRInfo?q=test_url',
    'Damaged goods'
);

$reject = new RejectDeliveryNote();
$response = $reject->handle($rejection);
```

### Helper methods

```php
$reject = new RejectDeliveryNote();

// Απόρριψη με mark
$response = $reject->rejectUsingMark(400002969517846, 'Damaged goods');

// Απόρριψη με QR URL
$response = $reject->rejectUsingQrUrl(
    'https://mydataapidev.aade.gr/TimologioQR/QRInfo?q=test_url',
    'Damaged goods'
);
```

### Πεδία DeliveryRejection

| Πεδίο                   | Τύπος        | Υποχρεωτικό | Περιγραφή                            |
|-------------------------|--------------|-------------|--------------------------------------|
| `qrUrl` ή `invoiceMark` | string ή int | Ναι         | Το URL του QR ή το MARK του δελτίου  |
| `rejectionReason`       | string       | Όχι         | Λόγος απόρριψης (max 150 χαρακτήρες) |

## RequestDeliveryNoteStatus - Αναζήτηση Κατάστασης Δελτίου

```shell
# production
https://mydatapi.aade.gr/myDATA/GetDeliveryNoteStatus

# development
https://mydataapidev.aade.gr/GetDeliveryNoteStatus
```

Αναζητά την τρέχουσα κατάσταση ενός δελτίου αποστολής και το πλήρες ιστορικό των συμβάντων του (lifecycle history).

### Παράδειγμα χρήσης

```php
use Firebed\AadeMyData\Http\DigitalGoodsMovement\RequestDeliveryNoteStatus;
use Firebed\AadeMyData\Enums\DigitalGoodsMovement\DeliveryStatus;
use Firebed\AadeMyData\Exceptions\MyDataException;

$request = new RequestDeliveryNoteStatus();

try {
    $response = $request->handle(111111111111111); // mark του δελτίου

    echo "Status: " . $response->getStatus()->label() . PHP_EOL;
    echo "Invoice Mark: " . $response->getInvoiceMark() . PHP_EOL;

    if ($response->getDispatchTimestamp()) {
        echo "Dispatch Time: " . $response->getDispatchTimestamp() . PHP_EOL;
    }

    // Εμφάνιση ιστορικού συμβάντων
    $history = $response->getLifecycleHistory();
    if ($history) {
        echo "\nΙστορικό Συμβάντων:" . PHP_EOL;
        foreach ($history as $event) {
            echo "- Event: " . $event->getEventType()->label() . PHP_EOL;
            echo "  Timestamp: " . $event->getEventTimestamp() . PHP_EOL;
            echo "  Actor VAT: " . $event->getActorVat() . PHP_EOL;

            if ($event->getMark()) {
                echo "  Mark: " . $event->getMark() . PHP_EOL;
            }

            // Έλεγχος για λεπτομέρειες μεταφοράς
            if ($event->getTransportDetails()) {
                $transport = $event->getTransportDetails();
                echo "  Vehicle: " . $transport->getVehicleNumber() . PHP_EOL;
            }

            // Έλεγχος για λεπτομέρειες παράδοσης
            if ($event->getOutcomeDetails()) {
                $outcome = $event->getOutcomeDetails();
                echo "  Outcome: " . $outcome->getOutcome()->label() . PHP_EOL;
            }

            // Έλεγχος για λεπτομέρειες απόρριψης
            if ($event->getRejectionDetails()) {
                $rejection = $event->getRejectionDetails();
                echo "  Rejection Reason: " . $rejection->getReason() . PHP_EOL;
            }
        }
    }
} catch (MyDataException $e) {
    echo "Σφάλμα επικοινωνίας: " . $e->getMessage();
}
```

### Πιθανές καταστάσεις δελτίου (DeliveryStatus)

- `REGISTERED` (1) - Εκδόθηκε
- `CANCELLED` (2) - Ακυρώθηκε
- `IN_TRANSIT` (3) - Σε διακίνηση
- `REJECTED` (4) - Απορρίφθηκε
- `DELIVERED_BY_CARRIER` (5) - Παραδόθηκε από τον μεταφορέα
- `FAILED_DELIVERY` (7) - Αποτυχία παράδοσης
- `COMPLETED` (8) - Ολοκληρώθηκε

### Τύποι Συμβάντων (DeliveryEventType)

- `RegisterTransfer` - Έναρξη διακίνησης / Μεταφόρτωση
- `ConfirmOutcome` - Επιβεβαίωση παραλαβής
- `Rejection` - Απόρριψη

## RequestGroupQrDetails - Αναζήτηση Στοιχείων Ομαδικού QR

```shell
# production
https://mydatapi.aade.gr/myDATA/RequestGroupQRDetails

# development
https://mydataapidev.aade.gr/RequestGroupQRDetails
```

Αναζητά τα στοιχεία ενός ομαδικού QR code που έχει δημιουργηθεί προηγουμένως.

### Παράδειγμα χρήσης

```php
use Firebed\AadeMyData\Http\DigitalGoodsMovement\RequestGroupQrDetails;
use Firebed\AadeMyData\Exceptions\MyDataException;

$request = new RequestGroupQrDetails();

try {
    $response = $request->handle('9946C6AA9DB3658C85CC3BF43DB726FE25BBC555');

    echo "Group ID: " . $response->getGroupId() . PHP_EOL;
    echo "Creator VAT: " . $response->getGroupQrCreatorVatNumber() . PHP_EOL;
    echo "Created at: " . $response->getCreatedAt() . PHP_EOL;
    echo "Expires at: " . $response->getExpiresAt() . PHP_EOL;
    echo "QR URLs Count: " . $response->getQrUrlsCount() . PHP_EOL;

    echo "\nQR URLs:" . PHP_EOL;
    foreach ($response->getQrUrls() as $qrUrl) {
        echo "  - " . $qrUrl . PHP_EOL;
    }
} catch (MyDataException $e) {
    echo "Σφάλμα επικοινωνίας: " . $e->getMessage();
}
```

## Απόκριση και Επεξεργασία Αποτελεσμάτων

### Response Objects

Οι μέθοδοι `RegisterTransfer`, `ConfirmDeliveryOutcome`, και `RejectDeliveryNote` επιστρέφουν `ResponseDoc` (συλλογή από αντικείμενα `Response` - `Firebed\AadeMyData\Models\DigitalGoodsMovement\Response`).

Κάθε `Response` περιέχει:
- `index`: Αριθμός Σειράς Οντότητας
- `statusCode`: Κωδικός Αποτελέσματος (`Success` ή άλλο)
- `transferMark`: Μοναδικός Αριθμός Εκκίνησης/Μεταφόρτωσης (μόνο για `RegisterTransfer`)
- `rejectMark`: Μοναδικός Αριθμός Απόρριψης (μόνο για `RejectDeliveryNote`)
- `deliveryOutcomeMark`: Μοναδικός Αριθμός Καταχώρησης (μόνο για `ConfirmDeliveryOutcome`)
- `errors`: Λίστα Σφαλμάτων (αν υπάρχουν)

```php
// Έλεγχος επιτυχίας
if ($response->first()->isSuccessful()) {
    echo "Success!" . PHP_EOL;

    // Ανάλογα με τη μέθοδο που καλέσατε:
    echo "Transfer Mark: " . $response->first()->getTransferMark() . PHP_EOL;          // RegisterTransfer
    echo "Reject Mark: " . $response->first()->getRejectMark() . PHP_EOL;              // RejectDeliveryNote
    echo "Outcome Mark: " . $response->first()->getDeliveryOutcomeMark() . PHP_EOL;    // ConfirmDeliveryOutcome
}

// Έλεγχος αποτυχίας
if ($response->first()->isFailed()) {
    echo "Errors: " . PHP_EOL;
    foreach ($response->first()->getErrors() as $error) {
        echo "- " . $error->getMessage() . PHP_EOL;
    }
}

// Διατρέχοντας όλα τα αποτελέσματα
foreach ($response as $item) {
    if ($item->isSuccessful()) {
        echo "Item {$item->getIndex()}: Success";
    }
}
```

### GroupQrCodeResponse

Η μέθοδος `GenerateGroupQrCode` επιστρέφει αντικείμενο `GroupQrCodeResponse`:

```php
$groupQrUrl = $response->getGroupQrUrl();      // URL του ομαδικού QR
$groupId = $response->getGroupId();            // Μοναδικό ID
$qrUrlsCount = $response->getQrUrlsCount();    // Πλήθος QR URLs
$expiresAt = $response->getExpiresAt();        // Ημερομηνία λήξης
$statusCode = $response->getStatusCode();      // Κωδικός κατάστασης
```

### DeliveryNoteStatusResponse

Η μέθοδος `RequestDeliveryNoteStatus` επιστρέφει αντικείμενο `DeliveryNoteStatusResponse`:

```php
$invoiceMark = $response->getInvoiceMark();           // Mark δελτίου
$status = $response->getStatus();                     // DeliveryStatus enum
$dispatchTime = $response->getDispatchTimestamp();    // Χρόνος αποστολής
$history = $response->getLifecycleHistory();          // Array από DeliveryEvent
```

### GroupQrDetailsResponse

Η μέθοδος `RequestGroupQrDetails` επιστρέφει αντικείμενο `GroupQrDetailsResponse`:

```php
$groupId = $response->getGroupId();
$creatorVat = $response->getGroupQrCreatorVatNumber();
$createdAt = $response->getCreatedAt();
$expiresAt = $response->getExpiresAt();
$qrUrls = $response->getQrUrls();                     // Array από URLs
$qrUrlsCount = $response->getQrUrlsCount();
```

> [!TIP]
> Όλες οι μέθοδοι που επιστρέφουν `Response` αντικείμενα υποστηρίζουν τις μεθόδους `isSuccessful()` και `isFailed()` για εύκολο έλεγχο κατάστασης.

> [!NOTE]
> Η σειρά των παραμέτρων στον constructor του `Location` είναι: `longitude` (γεωγραφικό μήκος), `latitude` (γεωγραφικό πλάτος).
