# Reverse delivery note purposes

Reason for a reverse delivery note (`reverseDeliveryNotePurpose`). Enum: `ReverseDeliveryNotePurpose`. Set it only when `reverseDeliveryNote` is `true`.

```php
use Firebed\AadeMyData\Enums\ReverseDeliveryNotePurpose;
use Firebed\AadeMyData\Models\InvoiceHeader;

$header = (new InvoiceHeader())
    ->setReverseDeliveryNote(true)
    ->setReverseDeliveryNotePurpose(ReverseDeliveryNotePurpose::INTRA_COMMUNITY_ACQUISITION);
```

| Code | Enum | `label()` | English |
|---|---|---|---|
| 1 | `ReverseDeliveryNotePurpose::NOT_OBLIGED_TO_ISSUE` | Μη Υπόχρεος Έκδοσης | Not obliged to issue |
| 2 | `ReverseDeliveryNotePurpose::REFUSAL_OR_INADVERTENT_NON_ISSUANCE` | Άρνηση Έκδοσης / Εκ Παραδρομής Μη Έκδοση | Refusal to issue / not issued by mistake |
| 3 | `ReverseDeliveryNotePurpose::INTRA_COMMUNITY_ACQUISITION` | Ενδοκοινοτική Απόκτηση | Intra-community acquisition |
| 4 | `ReverseDeliveryNotePurpose::THIRD_COUNTRY_ACQUISITION` | Απόκτηση Τρίτη Χώρα | Third-country acquisition |
| 5 | `ReverseDeliveryNotePurpose::REVERSE_CHARGE` | Αντίστροφη Υποχρέωση | Reverse charge |
