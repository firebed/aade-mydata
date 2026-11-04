# Digital transaction fee categories

Digital transaction fee rate, formerly stamp duty (`stampDutyPercentCategory`). Enum: `StampCategory`.

```php
use Firebed\AadeMyData\Enums\StampCategory;
use Firebed\AadeMyData\Models\InvoiceDetails;

$row = (new InvoiceDetails())
    ->setStampDutyPercentCategory(StampCategory::RATE_3_6)
    ->setStampDutyAmount(3.6);
```

For invoice-level taxes use `TaxTotals` with `TaxType::STAMP_DUTY`. See [Tax types](./tax-types).

| Code | Enum | `label()` | English | Rate |
|---|---|---|---|---|
| 1 | `StampCategory::RATE_1_2` | Συντελεστής 1.2 % | Rate 1.2% | 1.2% |
| 2 | `StampCategory::RATE_2_4` | Συντελεστής 2.4 % | Rate 2.4% | 2.4% |
| 3 | `StampCategory::RATE_3_6` | Συντελεστής 3.6 % | Rate 3.6% | 3.6% |
| 4 | `StampCategory::OTHER_CASES` | Λοιπές περιπτώσεις Ψηφιακού Τέλους Συναλλαγής | Other digital transaction fee cases | amount |

`amount`: no fixed rate, you send the amount.
