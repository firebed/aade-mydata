# Fees categories

Fees rate (`feesPercentCategory`). Enum: `FeesPercentCategory`.

```php
use Firebed\AadeMyData\Enums\FeesPercentCategory;
use Firebed\AadeMyData\Models\InvoiceDetails;

$row = (new InvoiceDetails())
    ->setFeesPercentCategory(FeesPercentCategory::RECYCLING_FEE)
    ->setFeesAmount(0.08);
```

For invoice-level taxes use `TaxTotals` with `TaxType::FEES`. See [Tax types](./tax-types).

| Code | Enum | `label()` | English | Rate |
|---|---|---|---|---|
| 1 | `FeesPercentCategory::MONTHLY_BILL_UP_TO_50_EUR` | Για μηνιαίο λογαριασμό μέχρι και 50 ευρώ 12% | Monthly bill up to €50: 12% | 12% |
| 2 | `FeesPercentCategory::MONTHLY_BILL_50_TO_100_EUR` | Για μηνιαίο λογαριασμό από 50,01 μέχρι και 100 ευρώ 15% | Monthly bill from €50.01 to €100: 15% | 15% |
| 3 | `FeesPercentCategory::MONTHLY_BILL_100_TO_150_EUR` | Για μηνιαίο λογαριασμό από 100,01 μέχρι και 150 ευρώ 18% | Monthly bill from €100.01 to €150: 18% | 18% |
| 4 | `FeesPercentCategory::MONTHLY_BILL_OVER_150_EUR` | Για μηνιαίο λογαριασμό από 150,01 ευρώ και άνω 20% | Monthly bill from €150.01 and over: 20% | 20% |
| 5 | `FeesPercentCategory::PREPAID_MOBILE_AIRTIME_12` | Τέλος καρτοκινητής επί της αξίας του χρόνου ομιλίας (12%) | Prepaid mobile fee on the value of airtime (12%) | 12% |
| 6 | `FeesPercentCategory::SUBSCRIPTION_TV_10` | Τέλος στη συνδρομητική τηλεόραση 10% | Pay-TV fee 10% | 10% |
| 7 | `FeesPercentCategory::FIXED_TELEPHONY_5` | Τέλος συνδρομητών σταθερής τηλεφωνίας 5% | Fixed telephony subscriber fee 5% | 5% |
| 8 | `FeesPercentCategory::PLASTIC_BAG_ENVIRONMENTAL_FEE` | Περιβαλλοντικό Τέλος & πλαστικής σακούλας ν. 2339/2001 αρ. 6α 0,07 ευρώ ανά τεμάχιο | Environmental fee & plastic bag, L. 2339/2001 art. 6a, €0.07 per piece | amount |
| 9 | `FeesPercentCategory::OLIVE_FLY_CONTROL_2` | Εισφορά δακοκτονίας 2% | Olive fruit fly control levy 2% | 2% |
| 10 | `FeesPercentCategory::OTHER_FEES` | Λοιπά τέλη | Other fees | amount |
| 11 | `FeesPercentCategory::OTHER_TAXES_FEES` | Τέλη Λοιπών Φόρων | Other taxes fees | amount |
| 12 | `FeesPercentCategory::OLIVE_FLY_CONTROL_AMOUNT` | ΤΕισφορά δακοκτονίας | Olive fruit fly control levy | amount |
| 13 | `FeesPercentCategory::MONTHLY_BILL_PER_CONNECTION_10` | Για μηνιαίο λογαριασμό κάθε σύνδεσης 10% | Monthly bill, per connection: 10% | 10% |
| 14 | `FeesPercentCategory::PREPAID_MOBILE_AIRTIME_10` | Τέλος καρτοκινητής επί της αξίας του χρόνου ομιλίας (10%) | Prepaid mobile fee on the value of airtime (10%) | 10% |
| 15 | `FeesPercentCategory::MOBILE_AGES_15_TO_29` | Τέλος κινητής και καρτοκινητής για φυσικά πρόσωπα ηλικίας 15 έως και 29 ετών 0% | Mobile and prepaid mobile fee for individuals aged 15 to 29: 0% | 0% |
| 16 | `FeesPercentCategory::PLASTIC_PRODUCTS_ENVIRONMENTAL_LEVY` | Εισφορά προστασίας περιβάλλοντος πλαστικών προϊόντων 0,04 λεπτά ανά τεμάχιο [άρθρο 4 ν. 4736/2020] | Environmental levy on plastic products, €0.04 per piece [art. 4 L. 4736/2020] | amount |
| 17 | `FeesPercentCategory::RECYCLING_FEE` | Τέλος ανακύκλωσης 0,08 λεπτά ανά τεμάχιο [άρθρο 80 ν. 4819/2021] | Recycling fee, €0.08 per piece [art. 80 L. 4819/2021] | amount |
| 18 | `FeesPercentCategory::TRANSIENT_STAY_FEE` | Τέλος διαμονής παρεπιδημούντων | Visitor stay fee | amount |
| 19 | `FeesPercentCategory::RESTAURANTS_GROSS_REVENUE_FEE` | Tέλος επί των ακαθάριστων εσόδων των εστιατορίων και συναφών καταστημάτων | Fee on the gross revenue of restaurants and similar businesses | amount |
| 20 | `FeesPercentCategory::ENTERTAINMENT_VENUES_GROSS_REVENUE_FEE` | Τέλος επί των ακαθάριστων εσόδων των κέντρων διασκέδασης | Fee on the gross revenue of entertainment venues | amount |
| 21 | `FeesPercentCategory::CASINOS_GROSS_REVENUE_FEE` | Τέλος επί των ακαθάριστων εσόδων των καζίνο | Fee on the gross revenue of casinos | amount |
| 22 | `FeesPercentCategory::OTHER_GROSS_REVENUE_FEES` | Λοιπά τέλη επί των ακαθάριστων εσόδων | Other fees on gross revenue | amount |

`amount`: no fixed rate, you send the amount.
