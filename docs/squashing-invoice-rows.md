# Σύνοψη Γραμμών Παραστατικού

Ο Πάροχος ηλεκτρονικής τιμολόγησης και τα ERP διαβιβάζουν υποχρεωτικά μόνο τη σύνοψη 
γραμμών και χαρακτηρισμών των παραστατικών. Οι λεπτομέρειες των γραμμών παραστατικού
θα πρέπει να ομαδοποιηθούν και να αθροιστούν κατάλληλα πριν την αποστολή στο myDATA.

Η σύνοψη γραμμών παραστατικού είναι μια σύνθετη διαδικασία και αναλύεται βάση της:
- κατηγορίας ΦΠΑ γραμμής
- κατηγορίας εξαίρεσης ΦΠΑ γραμμής
- κατηγορίας παρακρατούμενων φόρων γραμμής
- κατηγορίας λοιπών φόρων γραμμής
- κατηγορίας Ψηφιακού Τέλους Συναλλαγής γραμμής
- κατηγορίας τελών γραμμής
- τύπου (recType) γραμμής

Μετά τη σύνοψη γραμμών παραστατικού, το API υπολογίζει αυτόματα τους χαρακτηρισμών ανά γραμμή.

### Σύνοψη Γραμμών

Για τη σύνοψη των γραμμών του παραστατικού αρκεί να καλέσετε τη μέθοδο `squashInvoiceRows` της κλάσης `Invoice`.
Η διαδικασία σύνοψης θα αντικαταστήσει τις γραμμές με τις συνολικές τους τιμές.

```php
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoiceDetails;

$invoice = new Invoice();
$invoice->addInvoiceDetails(new InvoiceDetails(...));
$invoice->addInvoiceDetails(new InvoiceDetails(...));
$invoice->addInvoiceDetails(new InvoiceDetails(...));
$invoice->addInvoiceDetails(new InvoiceDetails(...));
// ... set more details

$invoice->squashInvoiceRows();
$invoice->summarizeInvoice();

print_r($invoice->toXml());
```

### Ανοχή ποσού ΦΠΑ

Το myDATA απορρίπτει μια γραμμή όταν το ποσό ΦΠΑ της απέχει περισσότερο από 1.00 από την καθαρή αξία
επί τον συντελεστή ΦΠΑ (σφάλμα 229). Μικρές διαφορές στρογγυλοποίησης σε πολλές γραμμές μπορεί να
ξεπεράσουν αυτό το όριο όταν αθροιστούν σε μία γραμμή. Με την επιλογή `vatAmountTolerance` η σύνοψη
ξεκινά νέα γραμμή με τις ίδιες κατηγορίες πριν το ποσό ΦΠΑ ξεπεράσει την ανοχή.

```php
$invoice->squashInvoiceRows(['vatAmountTolerance' => 1.00]);
```

Χωρίς την επιλογή (`null`), οι γραμμές με τις ίδιες κατηγορίες συνοψίζονται πάντα σε μία γραμμή.

### Ομάδες γραμμών

Η μέθοδος `groups` της κλάσης `SquashInvoiceRows` επιστρέφει τις αρχικές γραμμές που συνοψίζονται σε κάθε
γραμμή, χωρίς να τις αθροίζει, με την ίδια σειρά που τις επιστρέφει η σύνοψη. Είναι χρήσιμη για έλεγχο μιας
συνοψισμένης γραμμής πριν την αποστολή και για αντιστοίχισή της στις αρχικές γραμμές.

```php
use Firebed\AadeMyData\Actions\SquashInvoiceRows;

$groups = (new SquashInvoiceRows())->groups($invoice->getInvoiceDetails(), ['vatAmountTolerance' => 1.00]);

foreach ($groups as $group) {
    // $group: οι αρχικές γραμμές (InvoiceDetails) μίας συνοψισμένης γραμμής
}
```

> [!CAUTION]
> Η σύνοψη γραμμών δεν πρέπει να εφαρμοστεί για όλα τα είδη των παραστατικών.
> Για παράδειγμα, τα δελτία αποστολής δε χρειάζονται σύνοψη γραμμών. 

## Παράδειγμα

### XML παραστατικού πριν τη σύνοψη

```xml
<invoice>
  ...
  <invoiceDetails>
    <netValue>4.03</netValue>
    <vatCategory>1</vatCategory>
    <vatAmount>0.97</vatAmount>
    <incomeClassification>
      <icls:classificationType>E3_561_001</icls:classificationType>
      <icls:classificationCategory>category1_1</icls:classificationCategory>
      <icls:amount>4.03</icls:amount>
    </incomeClassification>
  </invoiceDetails>
  <invoiceDetails>
    <netValue>4.03</netValue>
    <vatCategory>1</vatCategory>
    <vatAmount>0.97</vatAmount>
    <incomeClassification>
      <icls:classificationType>E3_561_001</icls:classificationType>
      <icls:classificationCategory>category1_1</icls:classificationCategory>
      <icls:amount>2.02</icls:amount>
    </incomeClassification>
    <incomeClassification>
      <icls:classificationType>E3_561_007</icls:classificationType>
      <icls:classificationCategory>category1_1</icls:classificationCategory>
      <icls:amount>2.01</icls:amount>
    </incomeClassification>
  </invoiceDetails>
  <invoiceDetails>
    <netValue>4.03</netValue>
    <vatCategory>1</vatCategory>
    <vatAmount>0.97</vatAmount>
    <incomeClassification>
      <icls:classificationType>E3_561_001</icls:classificationType>
      <icls:classificationCategory>category1_1</icls:classificationCategory>
      <icls:amount>2.02</icls:amount>
    </incomeClassification>
    <incomeClassification>
      <icls:classificationType>E3_561_007</icls:classificationType>
      <icls:classificationCategory>category1_1</icls:classificationCategory>
      <icls:amount>2.01</icls:amount>
    </incomeClassification>
  </invoiceDetails>
  ...
</invoice>
  ```

### XML παραστατικού μετά τη σύνοψη

```xml
<invoice>
  ...
  <invoiceDetails>
    <netValue>12.09</netValue>
    <vatCategory>1</vatCategory>
    <vatAmount>2.91</vatAmount>
    <incomeClassification>
      <icls:classificationType>E3_561_001</icls:classificationType>
      <icls:classificationCategory>category1_1</icls:classificationCategory>
      <icls:amount>8.07</icls:amount>
      <icls:id>1</icls:id>
    </incomeClassification>
    <incomeClassification>
      <icls:classificationType>E3_561_007</icls:classificationType>
      <icls:classificationCategory>category1_1</icls:classificationCategory>
      <icls:amount>4.02</icls:amount>
      <icls:id>2</icls:id>
    </incomeClassification>
  </invoiceDetails>
  ...
</invoice>
```
