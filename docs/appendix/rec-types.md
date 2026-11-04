# Line types

Type of an invoice row (`recType`). Enum: `RecType`.

```php
use Firebed\AadeMyData\Enums\RecType;
use Firebed\AadeMyData\Models\InvoiceDetails;

$row = (new InvoiceDetails())->setRecType(RecType::FEES_LINE_WITH_VAT);
```

| Code | Enum | `label()` | English |
|---|---|---|---|
| 1 | `RecType::WITHHELD_TAXES_LINE` | Ειδική Γραμμή Παρακρατούμενων Φόρων Ανενεργός - για μελλοντική χρήση | Special withheld taxes line. Inactive, for future use |
| 2 | `RecType::FEES_LINE_WITH_VAT` | Γραμμή Τέλους με Φ.Π.Α. | Fee line with VAT |
| 3 | `RecType::OTHER_TAXES_LINE_WITH_VAT` | Γραμμή Λοιπών Φόρων με Φ.Π.Α. | Other taxes line with VAT |
| 4 | `RecType::STAMP_DUTY_LINE` | Ειδική Γραμμή Ψηφιακού Τέλους Συναλλαγής Ανενεργός - για μελλοντική χρήση | Special digital transaction fee line. Inactive, for future use |
| 5 | `RecType::DEDUCTIONS_LINE` | Ειδική Γραμμή Κρατήσεων Ανενεργός - για μελλοντική χρήση | Special deductions line. Inactive, for future use |
| 6 | `RecType::GIFT_VOUCHER` | Δωροεπιταγή | Gift voucher |
| 7 | `RecType::NEGATIVE_VALUES` | Αρνητικό πρόσημο αξιών | Negative values |

- Codes `1`, `4` and `5` are inactive, reserved for future use.
- Code `7` is valid only for invoice types 17.3, 17.4, 17.5 and 17.6. It means the row amounts are negative.
