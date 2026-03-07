# Τύπος Invoice

Ο τύπος `Invoice` αντιπροσωπεύει ένα αντικείμενο που περιέχει πληροφορίες
για τα παραστατικά που θα διαβιβαστούν ή έχουν διαβιβαστεί στο σύστημα του ΑΑΔΕ myDATA.

| Πεδίο                                                | Υποχρεωτικό | Περιγραφή                                                                                                                                              |
|------------------------------------------------------|-------------|--------------------------------------------------------------------------------------------------------------------------------------------------------|
| uid                                                  | Όχι         | Αναγνωριστικό Παραστατικού                                                                                                                             |
| mark                                                 | Όχι         | Μοναδικός Αριθμός Καταχώρησης Παραστατικού                                                                                                             |
| cancelledByMark                                      | Όχι         | Μοναδικός Αριθμός Καταχώρησης Ακυρωτικού                                                                                                               |
| authenticationCode                                   | Όχι         | Συμβολοσειρά Αυθεντικοποίησης                                                                                                                          |
| transmissionFailure                                  | Όχι         | Αδυναμία Επικοινωνίας Παρόχου ή Αδυναμία Διαβίβασης Δεδομένων από ERP                                                                                  |
| [**issuer**](party-type)                             | Όχι         | Εκδότης Παραστατικού                                                                                                                                   |
| [**counterpart**](party-type)                        | Όχι         | Λήπτης Παραστατικού                                                                                                                                    |
| [**paymentMethods[]**](payment-method-detail-type)   | Όχι         | Τρόποι Πληρωμής                                                                                                                                        |
| [**invoiceHeader**](invoice-header-type)             | **Ναι**     | Επικεφαλίδα Παραστατικού                                                                                                                               |
| [**invoiceDetails[]**](invoice-row-type)             | **Ναι**     | Γραμμές Παραστατικού                                                                                                                                   |
| [**taxesTotals[]**](tax-totals-type)                 | Όχι         | Σύνολα Φόρων                                                                                                                                           |
| [**invoiceSummary**](invoice-summary-type)           | **Ναι**     | Περίληψη Παραστατικού                                                                                                                                  |
| qrCodeUrl                                            | Όχι         | Κωδικοποιημένο αλφαριθμητικό για να χρησιμοποιηθεί από τα προγράμματα για τη δημιουργία QR Code τύπου Url                                              |
| downloadingInvoiceUrl                                | Όχι         | URL για λήψη παραστατικού (έγκυρο μόνο στην περίπτωση διαβίβασης μέσω παρόχου) <sub><sup>v1.0.12</sup></sub>                                          |
| packingsDeclarations[]                               | Όχι         | Δηλώσεις Συσκευασιών Διακίνησης (έγκυρο μόνο για παραστατικά διακίνησης) <sub><sup>v2.0.1</sup></sub>                                                 |
| invoiceDeliveryStatus                                | Όχι         | Κατάσταση Παραστατικού Δελτίου Διακίνησης (read-only, παρέχεται από το myDATA κατά την ανάκτηση) <sub><sup>v2.0.1</sup></sub>                         |
| deliveryLifecycle                                    | Όχι         | Το σύνολο των γεγονότων του κύκλου ζωής του παραστατικού διακίνησης (read-only, παρέχεται από το myDATA κατά την ανάκτηση) <sub><sup>v2.0.1</sup></sub> |

> [!NOTE]
> Τα πεδία `uid`, `mark`, `cancelledByMark` και `authenticationCode` συμπληρώνονται από την υπηρεσία.

## Παρατηρήσεις

- Το uid αποτελεί το αναγνωριστικό κάθε παραστατικού και συμπληρώνεται από την Υπηρεσία.
  Γενικά υπολογίζεται από το SHA-1 hash των ακόλουθων πεδίων του παραστατικού τα οποία είναι :
    - ΑΦΜ Eκδότη
    - Ημερομηνία Έκδοσης
    - Αριθμός Εγκατάστασης στο Μητρώο του Taxis
    - Τύπος Παραστατικού
    - Σειρά
    - ΑΑ
    - Τύπος Απόκλισης Παραστατικού (εφόσον υπάρχει)
      Ειδικά για τα παραστατικά των κατηγοριών Β1 (Μη Αντικριζόμενα Παραστατικά
      Λήπτη ημεδαπής / αλλοδαπής) και Β2 (Αντικριζόμενα Παραστατικά Λήπτη
      ημεδαπής / αλλοδαπής) στον υπολογισμό του uid συμμετέχει και το ΑΦΜ του
      λήπτη. Κατά τη χρήση του αλγόριθμου SHA-1 χρησιμοποιείται κωδικοποίηση ISO-8859-7.
- Το `mark` αποτελεί τον Μοναδικό Αριθμό Καταχώρησης του παραστατικού (Μ.ΑΡ.Κ)
- Στο στοιχείο `taxesTotals` θα περιλαμβάνονται φόροι όλων των κατηγοριών, εκτός
  του ΦΠΑ, οι οποίοι αφορούν όλο το παραστατικό σαν σύνολο. Σε περίπτωση που ο
  χρήστης κάνει χρήση αυτού του στοιχείου, δε θα μπορεί να εισάγει φόρους εκτός
  του ΦΠΑ σε κάθε γραμμή του παραστατικού ξεχωριστά.
- Ο Μοναδικός Αριθμός Καταχώρησης Ακυρωτικού εμφανίζεται κατά τη λήψη μόνο
  εφόσον το εν λόγω παραστατικό έχει ακυρωθεί και συμπληρώνεται με το ΜΑΡΚ της
  ακύρωσης.
- Το authenticationCode αποτελεί τη συμβολοσειρά αυθεντικοποίησης κάθε
  παραστατικού και συμπληρώνεται από την Υπηρεσία για την περίπτωση που η
  αποστολή γίνεται μέσω Παρόχου Ηλεκτρονικής Τιμολόγησης. Υπολογίζεται από το
  SHA-1 hash 8 πεδίων του παραστατικού τα οποία είναι :
    - ΑΦΜ Eκδότη
    - Ημερομηνία Έκδοσης
    - Αριθμός Εγκατάστασης στο Μητρώο του Taxis
    - Τύπος Παραστατικού
    - Σειρά
    - ΑΑ
    - Μ.ΑΡ.Κ Παραστατικού
    - Συνολική Αξία Παραστατικού
    - Σύνολο Αξίας Φ.Π.Α. Παραστατικού
    - ΑΦΜ Λήπτη
- Το πεδίο qrCodeUrl περιέχει ένα url του οποίου το query string είναι
  κωδικοποιημένο, προκειμένου να χρησιμοποιηθεί από τις εφαρμογές για την
  παραγωγή/εισαγωγή στα παραστατικά QR Code τύπου url, το οποίο θα οδηγεί
  (κατά την ανάγνωση του) σε σελίδα της Υπηρεσίας για την επισκόπηση του
  παραστατικού.
- Επιτρεπτές τιμές για το πεδίο `transmissionFailure` είναι:
    - **1**. Στην περίπτωση αδυναμίας επικοινωνίας οντότητας με τον πάροχο κατά την έκδοση/διαβίβαση παραστατικού (μόνο για παρόχους).
    - **2**. Στην περίπτωση αδυναμίας επικοινωνίας του παρόχου με το myDATA κατά την έκδοση/διαβίβαση παραστατικού (μόνο για παρόχους).
    - **3**. Απώλεια διασύνδεσης (είναι επιτρεπτή μόνο για περίπτωση αποστολής από ERP).

## Νέα Πεδία v2.0.1 - Ψηφιακή Διακίνηση Αγαθών

### packingsDeclarations

Το πεδίο `packingsDeclarations` είναι λίστα από αντικείμενα `PackagingDetail` που περιέχει δηλώσεις συσκευασιών για παραστατικά διακίνησης.

```php
use Firebed\AadeMyData\Models\DigitalGoodsMovement\PackagingDetail;
use Firebed\AadeMyData\Enums\DigitalGoodsMovement\PackagingType;

$invoice = new Invoice();

// Προσθήκη δήλωσης συσκευασίας
$invoice->addPackagingDeclaration(new PackagingDetail(PackagingType::PALLET, 10));
$invoice->addPackagingDeclaration(new PackagingDetail(PackagingType::BOX, 50));

// Ή ορισμός όλων μαζί
$invoice->setPackingsDeclarations([
    new PackagingDetail(PackagingType::CRATE, 5),
    new PackagingDetail(PackagingType::BARREL, 2),
]);
```

### invoiceDeliveryStatus

Το πεδίο `invoiceDeliveryStatus` είναι **read-only** και επιστρέφεται από το myDATA κατά την ανάκτηση παραστατικών διακίνησης. Περιέχει την τρέχουσα κατάσταση του δελτίου αποστολής.

```php
$invoice = $myData->requestDocs(...);

if ($invoice->getInvoiceDeliveryStatus()) {
    $status = $invoice->getInvoiceDeliveryStatus();
    echo "Delivery Status: " . $status->label();

    // Πιθανές τιμές:
    // - REGISTERED (1): Εκδόθηκε
    // - CANCELLED (2): Ακυρώθηκε
    // - IN_TRANSIT (3): Σε διακίνηση
    // - REJECTED (4): Απορρίφθηκε
    // - DELIVERED_BY_CARRIER (5): Παραδόθηκε από τον μεταφορέα
    // - FAILED_DELIVERY (7): Αποτυχία παράδοσης
    // - COMPLETED (8): Ολοκληρώθηκε
}
```

### deliveryLifecycle

Το πεδίο `deliveryLifecycle` είναι **read-only** και επιστρέφεται από το myDATA κατά την ανάκτηση παραστατικών διακίνησης. Περιέχει το πλήρες ιστορικό γεγονότων του κύκλου ζωής του δελτίου αποστολής.

```php
$invoice = $myData->requestDocs(...);

if ($invoice->getDeliveryLifecycle()) {
    $lifecycle = $invoice->getDeliveryLifecycle();

    foreach ($lifecycle as $event) {
        echo "Event: " . $event->getEventType()->label() . "\n";
        echo "Timestamp: " . $event->getEventTimestamp() . "\n";
        echo "Actor VAT: " . $event->getActorVat() . "\n";

        // Έλεγχος για λεπτομέρειες μεταφοράς
        if ($event->getTransportDetails()) {
            $transport = $event->getTransportDetails();
            echo "Vehicle: " . $transport->getVehicleNumber() . "\n";
        }

        // Έλεγχος για λεπτομέρειες παράδοσης
        if ($event->getOutcomeDetails()) {
            $outcome = $event->getOutcomeDetails();
            echo "Outcome: " . $outcome->getOutcome()->label() . "\n";
        }

        // Έλεγχος για λεπτομέρειες απόρριψης
        if ($event->getRejectionDetails()) {
            $rejection = $event->getRejectionDetails();
            echo "Rejection Reason: " . $rejection->getReason() . "\n";
        }
    }
}
```

> [!TIP]
> Για περισσότερες πληροφορίες σχετικά με την Ψηφιακή Διακίνηση Αγαθών, δείτε την [τεκμηρίωση Digital Goods Movement](../http/digital-goods-movement).
