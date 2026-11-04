# Other taxes categories

Other taxes rate (`otherTaxesPercentCategory`). Enum: `OtherTaxesPercentCategory`.

```php
use Firebed\AadeMyData\Enums\OtherTaxesPercentCategory;
use Firebed\AadeMyData\Models\InvoiceDetails;

$row = (new InvoiceDetails())
    ->setOtherTaxesPercentCategory(OtherTaxesPercentCategory::HOTELS_3_STARS_3_EUR)
    ->setOtherTaxesAmount(3.0);
```

For invoice-level taxes use `TaxTotals` with `TaxType::OTHER_TAXES`. See [Tax types](./tax-types).

| Code | Enum | `label()` | English | Rate |
|---|---|---|---|---|
| 1 | `OtherTaxesPercentCategory::FIRE_INSURANCE_PREMIUMS_A1` | α1) ασφάλιστρα κλάδου πυρός 20% | a1) fire insurance premiums 20% | 15% |
| 2 | `OtherTaxesPercentCategory::FIRE_INSURANCE_PREMIUMS_A2` | α2) ασφάλιστρα κλάδου πυρός 20% | a2) fire insurance premiums 20% | 5% |
| 3 | `OtherTaxesPercentCategory::LIFE_INSURANCE_PREMIUMS_4` | β) ασφάλιστρα κλάδου ζωής 4% | b) life insurance premiums 4% | 4% |
| 4 | `OtherTaxesPercentCategory::OTHER_INSURANCE_PREMIUMS_15` | γ) ασφάλιστρα λοιπών κλάδων 15% | c) other insurance premiums 15% | 15% |
| 5 | `OtherTaxesPercentCategory::EXEMPT_INSURANCE_PREMIUMS` | δ) απαλλασσόμενα φόρου ασφαλίστρων 0% | d) insurance premiums exempt from tax 0% | 0% |
| 6 | `OtherTaxesPercentCategory::HOTELS_1_2_STARS_0_50_EUR` | Ξενοδοχεία 1-2 αστέρων 0,50€ | Hotels 1-2 stars €0.50 | amount |
| 7 | `OtherTaxesPercentCategory::HOTELS_3_STARS_1_50_EUR` | Ξενοδοχεία 3 αστέρων 1,50€ | Hotels 3 stars €1.50 | amount |
| 8 | `OtherTaxesPercentCategory::HOTELS_4_STARS_3_EUR` | Ξενοδοχεία 4 αστέρων 3,00€ | Hotels 4 stars €3.00 | amount |
| 9 | `OtherTaxesPercentCategory::HOTELS_5_STARS_4_EUR` | Ξενοδοχεία 4 αστέρων 4,00€ | Hotels 5 stars €4.00 (see note) | amount |
| 10 | `OtherTaxesPercentCategory::FURNISHED_ROOMS_0_50_EUR` | Ενοικιαζόμενα - επιπλωμένα δωμάτια - διαμερίσματα 0,50€ | Rented furnished rooms - apartments €0.50 | amount |
| 11 | `OtherTaxesPercentCategory::TV_ADVERTISING_TAX_5` | Ειδικός Φόρος στις διαφημίσεις που προβάλλονται από την τηλεόραση (ΕΦΤΔ) 5% | Special tax on TV advertising (EFTD) 5% | 5% |
| 12 | `OtherTaxesPercentCategory::LUXURY_TAX_IMPORTED_GOODS_10` | 3.1 Φόρος πολυτελείας 10% επί της φορολογητέας αξίας για τα ενδοκοινοτικώς αποκτούμενα και εισαγόμενα από τρίτες χώρες 10% | 3.1 Luxury tax 10% on the taxable value of goods acquired intra-community or imported from third countries | 10% |
| 13 | `OtherTaxesPercentCategory::LUXURY_TAX_DOMESTIC_GOODS_10` | 3.2 Φόρος πολυτελείας 10% επί της τιμής πώλησης προ Φ.Π.Α. για τα εγχωρίως παραγόμενα είδη 10% | 3.2 Luxury tax 10% on the pre-VAT sale price of goods produced in Greece | 10% |
| 14 | `OtherTaxesPercentCategory::CASINO_TICKETS_STATE_DUTY_80` | Δικαίωμα του Δημοσίου στα εισιτήρια των καζίνο (80% επί του εισιτηρίου) | State duty on casino tickets (80% of the ticket) | 80% |
| 15 | `OtherTaxesPercentCategory::FIRE_INSURANCE_PREMIUMS_20` | ασφάλιστρα κλάδου πυρός 20% | Fire insurance premiums 20% | 20% |
| 16 | `OtherTaxesPercentCategory::CUSTOMS_DUTIES` | Τελωνειακοί Δασμοί-Φόροι | Customs duties - taxes | amount |
| 17 | `OtherTaxesPercentCategory::OTHER_TAXES` | Λοιποί Φόροι | Other taxes | amount |
| 18 | `OtherTaxesPercentCategory::OTHER_TAXES_SURCHARGES` | Επιβαρύνσεις Λοιπών Φόρων | Other taxes surcharges | amount |
| 19 | `OtherTaxesPercentCategory::EXCISE_DUTY` | ΕΦΚ | Excise duty (EFK) | amount |
| 20 | `OtherTaxesPercentCategory::HOTELS_1_2_STARS_1_50_EUR` | Ξενοδοχεία 1-2 αστέρων 1,50€ (ανά Δωμ./Διαμ.) | Hotels 1-2 stars €1.50 (per room/apartment) | amount |
| 21 | `OtherTaxesPercentCategory::HOTELS_3_STARS_3_EUR` | Ξενοδοχεία 3 αστέρων 3, 00€ (ανά Δωμ./Διαμ.) | Hotels 3 stars €3.00 (per room/apartment) | amount |
| 22 | `OtherTaxesPercentCategory::HOTELS_4_STARS_7_EUR` | Ξενοδοχεία 4 αστέρων 7, 00€ (ανά Δωμ./Διαμ.) | Hotels 4 stars €7.00 (per room/apartment) | amount |
| 23 | `OtherTaxesPercentCategory::HOTELS_5_STARS_10_EUR` | Ξενοδοχεία 5 αστέρων 10, 00€ (ανά Δωμ./Διαμ.) | Hotels 5 stars €10.00 (per room/apartment) | amount |
| 24 | `OtherTaxesPercentCategory::FURNISHED_ROOMS_1_50_EUR` | Ενοικιαζόμενα επιπλωμένα δωμάτια – διαμερίσματα 1, 50€ (ανά Δωμ./Διαμ.) | Rented furnished rooms - apartments €1.50 (per room/apartment) | amount |
| 25 | `OtherTaxesPercentCategory::SHORT_TERM_RENTALS_1_50_EUR` | Ακίνητα βραχυχρόνιας μίσθωσης 1,50€ | Short-term rental properties €1.50 | amount |
| 26 | `OtherTaxesPercentCategory::SHORT_TERM_RENTAL_HOUSES_OVER_80_SQM_10_EUR` | Ακίνητα βραχυχρόνιας μίσθωσης μονοκατοικίες άνω των 80 τ.μ. 10,00€ | Short-term rental detached houses over 80 m² €10.00 | amount |
| 27 | `OtherTaxesPercentCategory::SELF_CATERING_VILLAS_10_EUR` | Αυτοεξυπηρετούμενα καταλύματα – τουριστικές επιπλωμένες επαύλεις (βίλες) 10,00€ | Self-catering accommodation - furnished tourist villas €10.00 | amount |
| 28 | `OtherTaxesPercentCategory::SHORT_TERM_RENTALS_0_50_EUR` | Ακίνητα βραχυχρόνιας μίσθωσης 0,50€ | Short-term rental properties €0.50 | amount |
| 29 | `OtherTaxesPercentCategory::SHORT_TERM_RENTAL_HOUSES_OVER_80_SQM_4_EUR` | Ακίνητα βραχυχρόνιας μίσθωσης μονοκατοικίες άνω των 80 τ.μ. 4,00€ | Short-term rental detached houses over 80 m² €4.00 | amount |
| 30 | `OtherTaxesPercentCategory::SELF_CATERING_VILLAS_4_EUR` | Αυτοεξυπηρετούμενα καταλύματα – τουριστικές επιπλωμένες επαύλεις (βίλες) 4,00€ | Self-catering accommodation - furnished tourist villas €4.00 | amount |

- `amount`: no fixed rate, you send the amount.
- Codes `1` and `2` are not accepted when sending.
- Code `9` is the 5-star rate. The AADE label says 4 stars.
