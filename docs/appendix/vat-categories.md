# VAT categories

VAT rate of an invoice row (`vatCategory`). Enum: `VatCategory`.

```php
use Firebed\AadeMyData\Enums\VatCategory;
use Firebed\AadeMyData\Models\InvoiceDetails;

$row = (new InvoiceDetails())->setVatCategory(VatCategory::RATE_24);

VatCategory::RATE_24->rate();  // 24.0
VatCategory::EXEMPT->isZero(); // true (also for WITHOUT_VAT)
```

| Code | Enum | `label()` | English | Rate |
|---|---|---|---|---|
| 1 | `VatCategory::RATE_24` | ΦΠΑ συντελεστής 24% | VAT rate 24% | 24% |
| 2 | `VatCategory::RATE_13` | ΦΠΑ συντελεστής 13% | VAT rate 13% | 13% |
| 3 | `VatCategory::RATE_6` | ΦΠΑ συντελεστής 6% | VAT rate 6% | 6% |
| 4 | `VatCategory::RATE_17` | ΦΠΑ συντελεστής 17% | VAT rate 17% | 17% |
| 5 | `VatCategory::RATE_9` | ΦΠΑ συντελεστής 9% | VAT rate 9% | 9% |
| 6 | `VatCategory::RATE_4` | ΦΠΑ συντελεστής 4% | VAT rate 4% | 4% |
| 7 | `VatCategory::EXEMPT` | Άνευ Φ.Π.Α. 0% | Without VAT 0% | 0% |
| 8 | `VatCategory::WITHOUT_VAT` | Εγγραφές χωρίς ΦΠΑ | Entries without VAT | - |
| 9 | `VatCategory::RATE_3_ARTICLE_31` | ΦΠΑ συντελεστής 3% (αρ.31 ν.5057/2023) | VAT rate 3% (art. 31 L. 5057/2023) | 3% |
| 10 | `VatCategory::RATE_4_ARTICLE_31` | ΦΠΑ συντελεστής 4% (αρ.31 ν.5057/2023) | VAT rate 4% (art. 31 L. 5057/2023) | 4% |

- Code `7` needs a VAT exemption reason. See [VAT exemption categories](./vat-exemption-categories).
- Use code `8` for accounting entries where VAT does not apply (e.g. payroll, depreciation).
