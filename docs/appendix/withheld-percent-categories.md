# Withholding tax categories

Withholding tax rate (`withheldPercentCategory`). Enum: `WithheldPercentCategory`.

```php
use Firebed\AadeMyData\Enums\WithheldPercentCategory;
use Firebed\AadeMyData\Models\InvoiceDetails;

$row = (new InvoiceDetails())
    ->setWithheldPercentCategory(WithheldPercentCategory::SERVICES_8)
    ->setWithheldAmount(8.0);
```

For invoice-level taxes use `TaxTotals` with `TaxType::WITHHELD_TAX`. See [Tax types](./tax-types).

| Code | Enum | `label()` | English | Rate |
|---|---|---|---|---|
| 1 | `WithheldPercentCategory::INTEREST_15` | Περιπτ. β’ - Τόκοι - 15% | Case b - Interest - 15% | 15% |
| 2 | `WithheldPercentCategory::ROYALTIES_20` | Περιπτ. γ’ - Δικαιώματα - 20% | Case c - Royalties - 20% | 20% |
| 3 | `WithheldPercentCategory::MANAGEMENT_CONSULTING_FEES_20` | Περιπτ. δ’ - Αμοιβές Συμβουλών Διοίκησης - 20% | Case d - Management consulting fees - 20% | 20% |
| 4 | `WithheldPercentCategory::TECHNICAL_WORKS_3` | Περιπτ. δ’ - Τεχνικά Έργα - 3% | Case d - Technical works - 3% | 3% |
| 5 | `WithheldPercentCategory::LIQUID_FUELS_AND_TOBACCO_1` | Υγρά καύσιμα και προϊόντα καπνοβιομηχανίας 1% | Liquid fuels and tobacco products 1% | 1% |
| 6 | `WithheldPercentCategory::OTHER_GOODS_4` | Λοιπά Αγαθά 4% | Other goods 4% | 4% |
| 7 | `WithheldPercentCategory::SERVICES_8` | Παροχή Υπηρεσιών 8% | Services 8% | 8% |
| 8 | `WithheldPercentCategory::ARCHITECTS_ENGINEERS_STUDIES_4` | Προκαταβλητέος Φόρος Αρχιτεκτόνων και Μηχανικών επί Συμβατικών Αμοιβών, για Εκπόνηση Μελετών και Σχεδίων 4% | Advance tax of architects and engineers on contract fees for studies and designs 4% | 4% |
| 9 | `WithheldPercentCategory::ARCHITECTS_ENGINEERS_OTHER_WORKS_10` | Προκαταβλητέος Φόρος Αρχιτεκτόνων και Μηχανικών επί Συμβατικών Αμοιβών, που αφορούν οποιασδήποτε άλλης φύσης έργα 10% | Advance tax of architects and engineers on contract fees for any other kind of work 10% | 10% |
| 10 | `WithheldPercentCategory::LAWYERS_FEES_15` | Προκαταβλητέος Φόρος στις Αμοιβές Δικηγόρων 15% | Advance tax on lawyers' fees 15% | 15% |
| 11 | `WithheldPercentCategory::EMPLOYMENT_INCOME` | Παρακράτηση Φόρου Μισθωτών Υπηρεσιών παρ. 1 αρ. 15 ν. 4172/2013 | Employment income tax withholding, par. 1 art. 15 L. 4172/2013 | amount |
| 12 | `WithheldPercentCategory::MERCHANT_NAVY_OFFICERS_15` | Παρακράτηση Φόρου Μισθωτών Υπηρεσιών παρ. 2 αρ. 15 ν. 4172/2013 - Αξιωματικών Εμπορικού Ναυτικού 15% | Employment income tax withholding, par. 2 art. 15 L. 4172/2013 - merchant navy officers 15% | 15% |
| 13 | `WithheldPercentCategory::MERCHANT_NAVY_LOWER_CREW_10` | Παρακράτηση Φόρου Μισθωτών Υπηρεσιών παρ. 2 αρ. 15 ν. 4172/2013 - Κατώτερο Πλήρωμα Εμπορικού Ναυτικού 10% | Employment income tax withholding, par. 2 art. 15 L. 4172/2013 - merchant navy lower crew 10% | 10% |
| 14 | `WithheldPercentCategory::SPECIAL_SOLIDARITY_CONTRIBUTION` | Παρακράτηση Ειδικής Εισφοράς Αλληλεγγύης | Special solidarity contribution withholding | amount |
| 15 | `WithheldPercentCategory::EMPLOYMENT_TERMINATION_COMPENSATION` | Παρακράτηση Φόρου Αποζημίωσης λόγω Διακοπής Σχέσης Εργασίας παρ. 3 αρ. 15 ν. 4172/2013 | Tax withholding on severance pay, par. 3 art. 15 L. 4172/2013 | amount |
| 16 | `WithheldPercentCategory::DOUBLE_TAXATION_TREATIES` | Παρακρατήσεις συναλλαγών αλλοδαπής βάσει συμβάσεων αποφυγής διπλής φορολογίας (Σ.Α.Δ.Φ.) | Withholdings on foreign transactions under double taxation treaties | amount |
| 17 | `WithheldPercentCategory::OTHER_WITHHOLDINGS` | Λοιπές Παρακρατήσεις Φόρου | Other tax withholdings | amount |
| 18 | `WithheldPercentCategory::DIVIDENDS_5` | Παρακράτηση Φόρου Μερίσματα 5% [περ.α παρ. 1 αρ. 64 ν. 4172/2013] | Dividend tax withholding 5% [case a par. 1 art. 64 L. 4172/2013] | 5% |

`amount`: no fixed rate, you send the amount.
