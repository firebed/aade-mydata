# Move purposes

Purpose of a goods movement. Enum: `MovePurpose`. Set it on the invoice header, or per row.

```php
use Firebed\AadeMyData\Enums\MovePurpose;
use Firebed\AadeMyData\Models\InvoiceDetails;
use Firebed\AadeMyData\Models\InvoiceHeader;

$header = (new InvoiceHeader())->setMovePurpose(MovePurpose::SALE);
$row = (new InvoiceDetails())->setMovePurposeLine(MovePurpose::RETURN);
```

| Code | Enum | `label()` | English |
|---|---|---|---|
| 1 | `MovePurpose::SALE` | Πώληση | Sale |
| 2 | `MovePurpose::SALE_FOR_THIRD_PARTIES` | Πώληση για Λογαριασμό Τρίτων | Sale on behalf of third parties |
| 3 | `MovePurpose::SAMPLING` | Δειγματισμός | Sampling |
| 4 | `MovePurpose::EXHIBITION` | Έκθεση | Exhibition |
| 5 | `MovePurpose::RETURN` | Επιστροφή | Return |
| 6 | `MovePurpose::SAFEKEEPING` | Φύλαξη | Safekeeping |
| 7 | `MovePurpose::PROCESSING_ASSEMBLY_DISASSEMBLY` | Επεξεργασία - Συναρμολόγηση - Αποσυναρμολόγηση | Processing - assembly - disassembly |
| 8 | `MovePurpose::INTERNAL_MOVEMENT` | Ενδοδιακίνηση | Movement between own premises |
| 9 | `MovePurpose::PURCHASE` | Αγορά | Purchase |
| 10 | `MovePurpose::SHIP_AND_AIRCRAFT_SUPPLY` | Εφοδιασμός πλοίων και αεροσκαφών | Supply of ships and aircraft |
| 11 | `MovePurpose::FREE_DISPOSAL` | Δωρεάν διάθεση | Free of charge |
| 12 | `MovePurpose::WARRANTY` | Εγγύηση | Warranty |
| 13 | `MovePurpose::LOAN_FOR_USE` | Χρησιδανεισμός | Loan for use |
| 14 | `MovePurpose::STORAGE_AT_THIRD_PARTIES` | Αποθήκευση σε Τρίτους | Storage at third parties |
| 15 | `MovePurpose::RETURN_FROM_SAFEKEEPING` | Επιστροφή από Φύλαξη | Return from safekeeping |
| 16 | `MovePurpose::RECYCLING` | Ανακύκλωση | Recycling |
| 17 | `MovePurpose::SCRAP_DESTRUCTION` | Καταστροφή άχρηστου υλικού | Destruction of unusable material |
| 18 | `MovePurpose::FIXED_ASSETS_MOVEMENT` | Διακίνηση Παγίων (Ενδοδιακίνηση) | Movement of fixed assets (between own premises) |
| 19 | `MovePurpose::OTHER_MOVEMENTS` | Λοιπές Διακινήσεις | Other movements |
| 20 | `MovePurpose::TRANSPORT_AND_COURIER` | Μεταφορές - Ταχυμεταφορές | Transport - courier |

> [!NOTE]
> Since AADE spec v1.0.11 you can no longer send codes 6, 15, 16, 17 and 18.
