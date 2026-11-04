# Invoice types

Type of the invoice (`invoiceType`). Enum: `InvoiceType`.

```php
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Models\InvoiceHeader;

$header = (new InvoiceHeader())->setInvoiceType(InvoiceType::TYPE_1_1);

InvoiceType::TYPE_11_1->isRetail();       // true
InvoiceType::TYPE_11_1->hasCounterpart(); // false
```

Other helpers on `InvoiceType`: `supportsFuelInvoice()`, `supportsDeliveryNote()`, `supportsSelfPricing()`, `supportsTaxFree()`, `supportsUnitOfMeasurement()`, `incomeClassifications()`, `expenseClassifications()`.

## Issuer invoices with a counterpart (domestic / foreign)

### Sales invoice

| Code | Enum | `label()` | English |
|---|---|---|---|
| 1.1 | `InvoiceType::TYPE_1_1` | Τιμολόγιο Πώλησης | Sales invoice |
| 1.2 | `InvoiceType::TYPE_1_2` | Τιμολόγιο Πώλησης / Ενδοκοινοτικές Παραδόσεις | Sales invoice / intra-community supplies |
| 1.3 | `InvoiceType::TYPE_1_3` | Τιμολόγιο Πώλησης / Παραδόσεις Τρίτων Χωρών | Sales invoice / third-country supplies |
| 1.4 | `InvoiceType::TYPE_1_4` | Τιμολόγιο Πώλησης / Πώληση για Λογαριασμό Τρίτων | Sales invoice / sale on behalf of third parties |
| 1.5 | `InvoiceType::TYPE_1_5` | Τιμολόγιο Πώλησης / Εκκαθάριση Πωλήσεων Τρίτων - Αμοιβή από Πωλήσεις Τρίτων | Sales invoice / third-party sales settlement - fee from third-party sales |
| 1.6 | `InvoiceType::TYPE_1_6` | Τιμολόγιο Πώλησης / Συμπληρωματικό Παραστατικό | Sales invoice / supplementary document |

### Service invoice

| Code | Enum | `label()` | English |
|---|---|---|---|
| 2.1 | `InvoiceType::TYPE_2_1` | Τιμολόγιο Παροχής | Service invoice |
| 2.2 | `InvoiceType::TYPE_2_2` | Τιμολόγιο Παροχής / Ενδοκοινοτική Παροχή Υπηρεσιών | Service invoice / intra-community services |
| 2.3 | `InvoiceType::TYPE_2_3` | Τιμολόγιο Παροχής / Παροχή Υπηρεσιών σε λήπτη Τρίτης Χώρας | Service invoice / services to a third-country recipient |
| 2.4 | `InvoiceType::TYPE_2_4` | Τιμολόγιο Παροχής / Συμπληρωματικό Παραστατικό | Service invoice / supplementary document |

### Title of acquisition

| Code | Enum | `label()` | English |
|---|---|---|---|
| 3.1 | `InvoiceType::TYPE_3_1` | Τίτλος Κτήσης (μή υπόχρεος Εκδότης) | Title of acquisition (issuer not obliged) |
| 3.2 | `InvoiceType::TYPE_3_2` | Τίτλος Κτήσης (άρνηση έκδοσης από υπόχρεο Εκδότη) | Title of acquisition (refusal to issue by an obliged issuer) |

### Credit invoice

| Code | Enum | `label()` | English |
|---|---|---|---|
| 5.1 | `InvoiceType::TYPE_5_1` | Πιστωτικό Τιμολόγιο / Συσχετιζόμενο | Credit invoice / correlated |
| 5.2 | `InvoiceType::TYPE_5_2` | Πιστωτικό Τιμολόγιο / Μη Συσχετιζόμενο | Credit invoice / not correlated |

### Self-delivery / own use

| Code | Enum | `label()` | English |
|---|---|---|---|
| 6.1 | `InvoiceType::TYPE_6_1` | Στοιχείο Αυτοπαράδοσης | Self-delivery document |
| 6.2 | `InvoiceType::TYPE_6_2` | Στοιχείο Ιδιοχρησιμοποίησης | Own-use document |

### Contract - income

| Code | Enum | `label()` | English |
|---|---|---|---|
| 7.1 | `InvoiceType::TYPE_7_1` | Συμβόλαιο - Έσοδο | Contract - income |

### Special document (income) - collection receipt

| Code | Enum | `label()` | English |
|---|---|---|---|
| 8.1 | `InvoiceType::TYPE_8_1` | Ενοίκια - Έσοδο | Rents - income |
| 8.2 | `InvoiceType::TYPE_8_2` | Τέλος ανθεκτικότητας κλιματικής κρίσης | Climate crisis resilience fee |
| 8.4 | `InvoiceType::TYPE_8_4` | Απόδειξη Είσπραξης POS | POS collection receipt |
| 8.5 | `InvoiceType::TYPE_8_5` | Απόδειξη Επιστροφής POS | POS refund receipt |

### Order slip

| Code | Enum | `label()` | English |
|---|---|---|---|
| 8.6 | `InvoiceType::TYPE_8_6` | Δελτίο Παραγγελίας Εστίασης | Restaurant order slip |

### Movement documents

| Code | Enum | `label()` | English |
|---|---|---|---|
| 9.1 | `InvoiceType::TYPE_9_1` | Δελτίο Αποστολής Συσχετιζόμενο | Delivery note, correlated |
| 9.2 | `InvoiceType::TYPE_9_2` | Συγκεντρωτικό Δελτίο Αποστολής | Summary delivery note |
| 9.3 | `InvoiceType::TYPE_9_3` | Παραστατικό Διακίνησης Μη Συσχετιζόμενο | Movement document, not correlated |

### Receiving note

| Code | Enum | `label()` | English |
|---|---|---|---|
| 10.1 | `InvoiceType::TYPE_10_1` | Δελτίο Ποσοτικής Παραλαβής Συσχετιζόμενο | Receiving note, correlated |
| 10.2 | `InvoiceType::TYPE_10_2` | Δελτίο Ποσοτικής Παραλαβής Μη Συσχετιζόμενο | Receiving note, not correlated |

## Issuer invoices without a counterpart (domestic / foreign)

### Retail documents

| Code | Enum | `label()` | English |
|---|---|---|---|
| 11.1 | `InvoiceType::TYPE_11_1` | ΑΛΠ | Retail sales receipt (ALP) |
| 11.2 | `InvoiceType::TYPE_11_2` | ΑΠΥ | Service receipt (APY) |
| 11.3 | `InvoiceType::TYPE_11_3` | Απλοποιημένο Τιμολόγιο | Simplified invoice |
| 11.4 | `InvoiceType::TYPE_11_4` | Πιστωτικό Στοιχ. Λιανικής | Retail credit note |
| 11.5 | `InvoiceType::TYPE_11_5` | Απόδειξη Λιανικής Πώλησης για Λογ/σμό Τρίτων | Retail sales receipt on behalf of third parties |

## Recipient invoices without a counterpart (domestic / foreign)

### Received retail documents

| Code | Enum | `label()` | English |
|---|---|---|---|
| 13.1 | `InvoiceType::TYPE_13_1` | Έξοδα - Αγορές Λιανικών Συναλλαγών ημεδαπής / αλλοδαπής | Expenses - domestic / foreign retail purchases |
| 13.2 | `InvoiceType::TYPE_13_2` | Παροχή Λιανικών Συναλλαγών ημεδαπής / αλλοδαπής | Domestic / foreign retail transactions |
| 13.3 | `InvoiceType::TYPE_13_3` | Κοινόχρηστα | Shared building expenses |
| 13.4 | `InvoiceType::TYPE_13_4` | Συνδρομές | Subscriptions |
| 13.30 | `InvoiceType::TYPE_13_30` | Παραστατικά Οντότητας ως Αναγράφονται από την ίδια (Δυναμικό) | Entity documents as recorded by the entity (dynamic) |
| 13.31 | `InvoiceType::TYPE_13_31` | Πιστωτικό Στοιχ. Λιανικής ημεδαπής / αλλοδαπής | Domestic / foreign retail credit note |

## Recipient invoices with a counterpart (domestic / foreign)

### Documents of exempt entities (domestic / foreign)

| Code | Enum | `label()` | English |
|---|---|---|---|
| 14.1 | `InvoiceType::TYPE_14_1` | Τιμολόγιο / Ενδοκοινοτικές Αποκτήσεις | Invoice / intra-community acquisitions |
| 14.2 | `InvoiceType::TYPE_14_2` | Τιμολόγιο / Αποκτήσεις Τρίτων Χωρών | Invoice / third-country acquisitions |
| 14.3 | `InvoiceType::TYPE_14_3` | Τιμολόγιο / Ενδοκοινοτική Λήψη Υπηρεσιών | Invoice / intra-community receipt of services |
| 14.4 | `InvoiceType::TYPE_14_4` | Τιμολόγιο / Λήψη Υπηρεσιών Τρίτων Χωρών | Invoice / third-country receipt of services |
| 14.5 | `InvoiceType::TYPE_14_5` | ΕΦΚΑ και λοιποί Ασφαλιστικοί Οργανισμοί | EFKA and other social security bodies |
| 14.30 | `InvoiceType::TYPE_14_30` | Παραστατικά Οντότητας ως Αναγράφονται από την ίδια (Δυναμικό) | Entity documents as recorded by the entity (dynamic) |
| 14.31 | `InvoiceType::TYPE_14_31` | Πιστωτικό ημεδαπής / αλλοδαπής | Domestic / foreign credit note |

### Contract - expense

| Code | Enum | `label()` | English |
|---|---|---|---|
| 15.1 | `InvoiceType::TYPE_15_1` | Συμβόλαιο - Έξοδο | Contract - expense |

### Special document (expense) - payment receipt

| Code | Enum | `label()` | English |
|---|---|---|---|
| 16.1 | `InvoiceType::TYPE_16_1` | Ενοίκιο Έξοδο | Rent - expense |

## Income - expense adjustment entries

### Entity entries

| Code | Enum | `label()` | English |
|---|---|---|---|
| 17.1 | `InvoiceType::TYPE_17_1` | Μισθοδοσία | Payroll |
| 17.2 | `InvoiceType::TYPE_17_2` | Αποσβέσεις | Depreciation |
| 17.3 | `InvoiceType::TYPE_17_3` | Λοιπές Εγγραφές Τακτοποίησης Εσόδων - Λογιστική Βάση | Other income adjustment entries - accounting basis |
| 17.4 | `InvoiceType::TYPE_17_4` | Λοιπές Εγγραφές Τακτοποίησης Εσόδων - Φορολογική Βάση | Other income adjustment entries - tax basis |
| 17.5 | `InvoiceType::TYPE_17_5` | Λοιπές Εγγραφές Τακτοποίησης Εξόδων - Λογιστική Βάση | Other expense adjustment entries - accounting basis |
| 17.6 | `InvoiceType::TYPE_17_6` | Λοιπές Εγγραφές Τακτοποίησης Εξόδων - Φορολογική Βάση | Other expense adjustment entries - tax basis |
