# TaxTotals

`TaxTotals` is one tax (other than VAT) that applies to the whole invoice. Add it with `Invoice::addTaxesTotals()`.

```php
use Firebed\AadeMyData\Enums\TaxType;
use Firebed\AadeMyData\Enums\WithheldPercentCategory;
use Firebed\AadeMyData\Models\TaxTotals;

$tax = new TaxTotals();
$tax->setTaxType(TaxType::WITHHELD_TAX);
$tax->setTaxCategory(WithheldPercentCategory::MANAGEMENT_CONSULTING_FEES_20);
$tax->setUnderlyingValue(500);
$tax->setTaxAmount(100);

$invoice->addTaxesTotals($tax);
```

| Field           | Type                    | Required | Description                                   |
|-----------------|-------------------------|----------|-----------------------------------------------|
| taxType         | `TaxType`               | Yes      | Tax type, see below                           |
| taxCategory     | enum or `int`           | No       | Tax category from the list of the `taxType`. The getter returns the `int` code |
| underlyingValue | `float`                 | No       | Value the tax applies to                      |
| taxAmount       | `float`                 | Yes      | Tax amount                                    |
| id              | `int`                   | No       | Line serial number                            |

| `taxType`               | `taxCategory` enum                                                                     |
|-------------------------|----------------------------------------------------------------------------------------|
| `TaxType::WITHHELD_TAX` | [`WithheldPercentCategory`](../appendix/withheld-percent-categories)                   |
| `TaxType::FEES`         | [`FeesPercentCategory`](../appendix/fees-percent-categories)                           |
| `TaxType::OTHER_TAXES`  | [`OtherTaxesPercentCategory`](../appendix/other-taxes-percent-categories)              |
| `TaxType::STAMP_DUTY`   | [`StampCategory`](../appendix/stamp-categories)                                        |
| `TaxType::DEDUCTIONS`   | none, leave `taxCategory` empty                                                        |

> [!WARNING]
> When you use `taxesTotals`, you cannot put taxes other than VAT on the invoice rows.

## More examples

```php
use Firebed\AadeMyData\Enums\FeesPercentCategory;
use Firebed\AadeMyData\Enums\OtherTaxesPercentCategory;

// Fees: subscription TV fee
$fees = new TaxTotals();
$fees->setTaxType(TaxType::FEES);
$fees->setTaxCategory(FeesPercentCategory::SUBSCRIPTION_TV_10);
$fees->setTaxAmount(52.66);

// Other taxes: life insurance premiums 4%
$other = new TaxTotals();
$other->setTaxType(TaxType::OTHER_TAXES);
$other->setTaxCategory(OtherTaxesPercentCategory::LIFE_INSURANCE_PREMIUMS_4);
$other->setTaxAmount(133.56);

// Deductions: no category
$deductions = new TaxTotals();
$deductions->setTaxType(TaxType::DEDUCTIONS);
$deductions->setTaxAmount(20);
```
