# Tax types

Type of an invoice-level tax (`taxType`). Enum: `TaxType`. Used in [TaxTotals](../models/tax-totals-type).

```php
use Firebed\AadeMyData\Enums\FeesPercentCategory;
use Firebed\AadeMyData\Enums\TaxType;
use Firebed\AadeMyData\Enums\WithheldPercentCategory;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\TaxTotals;

$invoice = new Invoice();
$invoice->addTaxesTotals(
    (new TaxTotals())
        ->setTaxType(TaxType::WITHHELD_TAX)
        ->setTaxCategory(WithheldPercentCategory::SERVICES_8)
        ->setUnderlyingValue(100.0)
        ->setTaxAmount(8.0)
);

// Does the category belong to the tax type?
TaxType::FEES->supportsTaxCategory(FeesPercentCategory::OTHER_FEES); // true
```

| Code | Enum | `label()` | English | Category enum |
|---|---|---|---|---|
| 1 | `TaxType::WITHHELD_TAX` | Παρακρατούμενος Φόρος | Withholding tax | [`WithheldPercentCategory`](./withheld-percent-categories) |
| 2 | `TaxType::FEES` | Τέλη | Fees | [`FeesPercentCategory`](./fees-percent-categories) |
| 3 | `TaxType::OTHER_TAXES` | Λοιποί Φόροι | Other taxes | [`OtherTaxesPercentCategory`](./other-taxes-percent-categories) |
| 4 | `TaxType::STAMP_DUTY` | Ψηφιακό Τέλος Συναλλαγής | Digital transaction fee (stamp duty) | [`StampCategory`](./stamp-categories) |
| 5 | `TaxType::DEDUCTIONS` | Κρατήσεις | Deductions | none |

Deductions have no category.
