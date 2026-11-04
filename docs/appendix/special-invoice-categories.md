# Special invoice categories

Special category of an invoice (`specialInvoiceCategory`). Enum: `SpecialInvoiceCategory`.

```php
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Enums\SpecialInvoiceCategory;
use Firebed\AadeMyData\Models\InvoiceHeader;

$header = (new InvoiceHeader())->setSpecialInvoiceCategory(SpecialInvoiceCategory::TAX_FREE);

// Does the invoice type accept Tax Free?
InvoiceType::TYPE_11_1->supportsTaxFree();
```

| Code | Enum | `label()` | English |
|---|---|---|---|
| 1 | `SpecialInvoiceCategory::SUBSIDIES_GRANTS` | Επιδοτήσεις – Επιχορηγήσεις | Subsidies - grants |
| 2 | `SpecialInvoiceCategory::HOTEL_RETAIL_INCOME` | Έσοδα Λιανικής Ξενοδοχείων | Hotel retail income |
| 3 | `SpecialInvoiceCategory::ACCOUNTING_ENTRY` | Λογιστική Εγγραφή | Accounting entry |
| 4 | `SpecialInvoiceCategory::TAX_FREE` | Tax Free | Tax Free |
| 5 | `SpecialInvoiceCategory::COMPLEX_DOMESTIC_FOREIGN_TRANSACTIONS` | Σύνθετες συναλλαγές ημεδαπής – αλλοδαπής | Complex domestic - foreign transactions |
| 6 | `SpecialInvoiceCategory::DECISION_139818_BENEFICIARIES` | Δικαιούχοι του άρθρου 3 της υπό στοιχεία 139818 ΕΞ2022/28.09.2022 (Β’5083) κοινής υπουργικής απόφασης | Beneficiaries of art. 3 of joint ministerial decision 139818 EX2022/28.09.2022 (B'5083) |
| 7 | `SpecialInvoiceCategory::AGRICULTURAL_PURCHASES_ARTICLE_41` | Αγορά αγροτικών αγαθών υπηρεσιών Άρθρο 41 του Κώδικα ΦΠΑ | Purchase of agricultural goods and services, art. 41 of the VAT Code |
| 8 | `SpecialInvoiceCategory::RETAIL_INCOME_FISCAL_DEVICE_AADE_1` | Έσοδα Λιανικών ΦΗΜ ΑΑΔΕ_1 | Retail income from fiscal devices (FHM), AADE_1 |
| 9 | `SpecialInvoiceCategory::RETAIL_INCOME_FISCAL_DEVICE_AADE_2` | Έσοδα Λιανικών ΦΗΜ ΑΑΔΕ_2 | Retail income from fiscal devices (FHM), AADE_2 |
| 10 | `SpecialInvoiceCategory::RETAIL_INCOME_FISCAL_DEVICE_DEVIATION` | Έσοδα Λιανικών ΦΗΜ Επιχείρησης Απόκλιση | Retail income from business fiscal devices (FHM), deviation |
| 11 | `SpecialInvoiceCategory::HEATING_ALLOWANCE` | Επίδομα Θέρμανσης | Heating allowance |
| 12 | `SpecialInvoiceCategory::RESTAURANT_TRANSACTIONS` | Συναλλαγές εστίασης | Food service transactions |
| 13 | `SpecialInvoiceCategory::CORRELATION_DIFFICULTY` | Ένδειξη Δυσχέρεια Συσχέτισης | Correlation difficulty flag |

- `4` (Tax Free): valid only when sent through an ERP, issued through a provider, or through timologio.
- `8` and `9`: read only. You cannot send them through an ERP or a provider.
- `13`: valid only for invoice types 11.4 and 14.30.
