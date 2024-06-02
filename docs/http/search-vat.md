# Αναζήτηση Βασικών Στοιχείων Μητρώου Επιχειρήσεων

Με τη χρήση αυτής της υπηρεσίας, τα νομικά πρόσωπα, οι νομικές οντότητες,
και τα φυσικά πρόσωπα με εισόδημα από επιχειρηματική δραστηριότητα μπορούν
να αναζητήσουν βασικές πληροφορίες, προκειμένου να διακριβώσουν τη φορολογική 
ή την επαγγελματική υπόσταση άλλων νομικών προσώπων ή νομικών οντοτήτων ή
φορολογουμένων/φυσικών προσώπων που ασκούν επιχειρηματική δραστηριότητα.

Το σύστημα παρέχει 2 τρόπους αναζήτησης βασικών στοιχείων μητρώου επιχειρήσεων:

- Μέσω της Υπηρεσίας Αναζήτησης Βασικών Στοιχείων Μητρώου Επιχειρήσεων
- Μέσω της Υπηρεσίας Vat Information Exchange System (VIES)

## Μέσω της Υπηρεσίας Αναζήτησης Βασικών Στοιχείων Μητρώου Επιχειρήσεων

Η υπηρεσία αυτή επιτρέπει την αναζήτηση όλων των Ελληνικών ΑΦΜ. Για την αναζήτηση
θα χρειαστείτε ένα `username` και ένα `password`.

Διαδικασία εγγραφής:

- Εγγραφή στην [υπηρεσία](https://www1.aade.gr/webtax/wspublicreg/faces/pages/wspublicreg/menu.xhtml) κάνοντας χρήση των κωδικών TAXISnet.
- Απόκτηση ειδικών κωδικών πρόσβασης μέσω της εφαρμογής [Διαχείριση Ειδικών Κωδικών](https://www1.aade.gr/sgsisapps/tokenservices/protected/displayConsole.htm).

Για περισσότερες λεπτομέρειες και για την εγγραφή επισκεφτείτε
την [Επίσημη Σελίδα του ΑΑΔΕ](https://www.aade.gr/anazitisi-basikon-stoiheion-mitrooy-epiheiriseon).

Μετά την εγγραφή, θα έχετε τα `username` και `password` που θα χρειαστείτε για την
χρήση της υπηρεσίας.

```php
use Firebed\VatRegistry\TaxisNet;
use Firebed\VatRegistry\VatException;

$username = 'your-username';
$password = 'your-password';

$taxis = new TaxisNet($username, $password);

try {
    $response = $taxis->handle('094014201');
    
    dd($response);
} catch (VatException $exception) {
    echo "Σφάλμα: " . $exception->getMessage();
}
```

Το αποτέλεσμα της παραπάνω κλήσης:

```php
Firebed\VatRegistry\VatEntity {
  +vatNumber: "094014201"
  +tax_authority_id: "1159"
  +tax_authority_name: "ΦΑΕ ΑΘΗΝΩΝ"
  +flag_description: "ΜΗ ΦΠ"
  +valid: true
  +validity_description: "ΕΝΕΡΓΟΣ ΑΦΜ"
  +firm_flag_description: "ΕΠΙΤΗΔΕΥΜΑΤΙΑΣ"
  +legalName: "ΤΡΑΠΕΖΑ ΕΘΝΙΚΗ ΤΗΣ ΕΛΛΑΔΟΣ ΑΝΩΝΥΜΗ ΕΤΑΙΡΕΙΑ"
  +commerce_title: ""
  +legal_status_description: "ΑΕ"
  +street: "ΑΙΟΛΟΥ"
  +street_number: "86"
  +postcode: "10559"
  +city: "ΑΘΗΝΑ"
  +registration_date: "1900-01-01"
  +stop_date: ""
  +normal_vat: true
  +firms: array:2 [
    0 => array:4 [
      "code" => "64191204"
      "description" => "ΥΠΗΡΕΣΙΕΣ ΤΡΑΠΕΖΩΝ"
      "kind" => "1"
      "kind_description" => "ΚΥΡΙΑ"
    ]
    1 => array:4 [
      "code" => "66221001"
      "description" => "ΥΠΗΡΕΣΙΕΣ ΑΣΦΑΛΙΣΤΙΚΟΥ ΠΡΑΚΤΟΡΑ ΚΑΙ ΑΣΦΑΛΙΣΤΙΚΟΥ ΣΥΜΒΟΥΛΟΥ"
      "kind" => "2"
      "kind_description" => "ΔΕΥΤΕΡΕΥΟΥΣΑ"
    ]
  ]
}
```

Σε περίπτωση που το ΑΦΜ δεν είναι έγκυρο επιστρέφεται τιμή `null`. Αν υπήρξε κάποιο άλλο πρόβλημα το `VatException` θα
περιέχει το σχετικό μήνυμα σφάλματος.

## Μέσω της Υπηρεσίας Vat Information Exchange System (VIES)

Με τη χρήση της Υπηρεσία VIES μπορείτε να επαληθεύσετε την εγκυρότητα του ΑΦΜ, 
που χορηγείται απο οποιοδήποτε κράτος μέλος της Ευρωπαϊκής Ένωσης. Οι λεπτομέρειες
που παρέχει είναι πιο περιορισμένες σε σχέση με την υπηρεσία της ΑΑΔΕ.

Η Υπηρεσία παρέχεται δωρεάν χωρίς εγγραφή σε κάποιο φορέα. Δέχεται 2 παραμέτρους:
- Τον κωδικό της χώρας (π.χ. EL για Ελλάδα)
- Τον ΑΦΜ που θέλετε να επαληθεύσετε.

```php
use Firebed\VatRegistry\VIES;
use Firebed\VatRegistry\VatException;

$taxis = new VIES();

try {
    $response = $taxis->handle('EL', '094014201');
    
    dd($response);
} catch (VatException $exception) {
    echo "Σφάλμα: " . $exception->getMessage();
}
```

Το αποτέλεσμα της παραπάνω κλήσης:

```php
Firebed\VatRegistry\VatEntity {
  +vatNumber: "094014201"
  +tax_authority_id: null
  +tax_authority_name: null
  +flag_description: null
  +valid: true
  +validity_description: null
  +firm_flag_description: null
  +legalName: "ΤΡΑΠΕΖΑ ΕΘΝΙΚΗ ΤΗΣ ΕΛΛΑΔΟΣ ΑΝΩΝΥΜΗ ΕΤΑΙΡΕΙΑ"
  +commerce_title: null
  +legal_status_description: null
  +street: "ΑΙΟΛΟΥ"
  +street_number: "86"
  +postcode: "10559"
  +city: "ΑΘΗΝΑ"
  +registration_date: null
  +stop_date: null
  +normal_vat: null
  +firms: []
}
```

Σε περίπτωση που το ΑΦΜ δεν είναι έγκυρο, η υπηρεσία επιστρέφει `null`.