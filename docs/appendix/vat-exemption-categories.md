# VAT exemption categories

Reason for a 0% VAT row (`vatExemptionCategory`). Enum: `VatExemption`. Articles refer to the Greek VAT Code (L. 5144/2024).

```php
use Firebed\AadeMyData\Enums\VatCategory;
use Firebed\AadeMyData\Enums\VatExemption;
use Firebed\AadeMyData\Models\InvoiceDetails;

$row = (new InvoiceDetails())
    ->setVatCategory(VatCategory::EXEMPT)
    ->setVatExemptionCategory(VatExemption::ARTICLE_33);
```

`ExpensesClassification::setVatExemptionCategory()` takes the same enum.

| Code | Enum | `label()` | English |
|---|---|---|---|
| 1 | `VatExemption::ARTICLE_2_AND_3` | Χωρίς ΦΠΑ - άρθρο 2 και 3 του Κώδικα ΦΠΑ | Without VAT - art. 2 and 3 of the VAT Code |
| 2 | `VatExemption::ARTICLE_5` | Χωρίς ΦΠΑ - άρθρο 5 του Κώδικα ΦΠΑ | Without VAT - art. 5 of the VAT Code |
| 3 | `VatExemption::ARTICLE_17` | Χωρίς ΦΠΑ - άρθρο 17 του Κώδικα ΦΠΑ | Without VAT - art. 17 of the VAT Code |
| 4 | `VatExemption::ARTICLE_18` | Χωρίς ΦΠΑ - άρθρο 18 του Κώδικα ΦΠΑ | Without VAT - art. 18 of the VAT Code |
| 5 | `VatExemption::ARTICLE_21` | Χωρίς ΦΠΑ - άρθρο 21 του Κώδικα ΦΠΑ | Without VAT - art. 21 of the VAT Code |
| 6 | `VatExemption::ARTICLE_24` | Χωρίς ΦΠΑ - άρθρο 24 του Κώδικα ΦΠΑ | Without VAT - art. 24 of the VAT Code |
| 7 | `VatExemption::ARTICLE_27` | Χωρίς ΦΠΑ - άρθρο 27 του Κώδικα ΦΠΑ | Without VAT - art. 27 of the VAT Code |
| 8 | `VatExemption::ARTICLE_29` | Χωρίς ΦΠΑ - άρθρο 29 του Κώδικα ΦΠΑ | Without VAT - art. 29 of the VAT Code |
| 9 | `VatExemption::ARTICLE_30` | Χωρίς ΦΠΑ - άρθρο 30 του Κώδικα ΦΠΑ | Without VAT - art. 30 of the VAT Code |
| 10 | `VatExemption::ARTICLE_31` | Χωρίς ΦΠΑ - άρθρο 31 του Κώδικα ΦΠΑ | Without VAT - art. 31 of the VAT Code |
| 11 | `VatExemption::ARTICLE_32` | Χωρίς ΦΠΑ - άρθρο 32 του Κώδικα ΦΠΑ | Without VAT - art. 32 of the VAT Code |
| 12 | `VatExemption::ARTICLE_32_OPEN_SEA_VESSELS` | Χωρίς ΦΠΑ - άρθρο 32 του Κώδικα ΦΠΑ - Πλοία Ανοικτής Θαλάσσης του Κώδικα ΦΠΑ | Without VAT - art. 32 of the VAT Code - open-sea vessels |
| 13 | `VatExemption::ARTICLE_32_PAR_1_C_OPEN_SEA_VESSELS` | Χωρίς ΦΠΑ - άρθρο 32 .1.γ. του Κώδικα ΦΠΑ - Πλοία Ανοικτής Θαλάσσης του Κώδικα ΦΠΑ | Without VAT - art. 32.1.c of the VAT Code - open-sea vessels |
| 14 | `VatExemption::ARTICLE_33` | Χωρίς ΦΠΑ - άρθρο 33 του Κώδικα ΦΠΑ | Without VAT - art. 33 of the VAT Code |
| 15 | `VatExemption::ARTICLE_44` | Χωρίς ΦΠΑ - άρθρο 44 του Κώδικα ΦΠΑ | Without VAT - art. 44 of the VAT Code |
| 16 | `VatExemption::ARTICLE_45` | Χωρίς ΦΠΑ - άρθρο 45 του Κώδικα ΦΠΑ | Without VAT - art. 45 of the VAT Code |
| 17 | `VatExemption::ARTICLE_47` | Χωρίς ΦΠΑ - άρθρο 47 του Κώδικα ΦΠΑ | Without VAT - art. 47 of the VAT Code |
| 18 | `VatExemption::ARTICLE_48` | Χωρίς ΦΠΑ - άρθρο 48 του Κώδικα ΦΠΑ | Without VAT - art. 48 of the VAT Code |
| 19 | `VatExemption::ARTICLE_54` | Χωρίς ΦΠΑ - άρθρο 54 του Κώδικα ΦΠΑ | Without VAT - art. 54 of the VAT Code |
| 20 | `VatExemption::VAT_INCLUDED_ARTICLE_50` | ΦΠΑ εμπεριεχόμενος - άρθρο 50 του Κώδικα ΦΠΑ | VAT included - art. 50 of the VAT Code |
| 21 | `VatExemption::VAT_INCLUDED_ARTICLE_51` | ΦΠΑ εμπεριεχόμενος - άρθρο 51 του Κώδικα ΦΠΑ | VAT included - art. 51 of the VAT Code |
| 22 | `VatExemption::VAT_INCLUDED_ARTICLE_52` | ΦΠΑ εμπεριεχόμενος - άρθρο 52 του Κώδικα ΦΠΑ | VAT included - art. 52 of the VAT Code |
| 23 | `VatExemption::VAT_INCLUDED_ARTICLE_53` | ΦΠΑ εμπεριεχόμενος - άρθρο 53 του Κώδικα ΦΠΑ | VAT included - art. 53 of the VAT Code |
| 24 | `VatExemption::ARTICLE_8` | Χωρίς ΦΠΑ - άρθρο 8 του Κώδικα ΦΠΑ | Without VAT - art. 8 of the VAT Code |
| 25 | `VatExemption::POL_1029_1995` | Χωρίς ΦΠΑ - ΠΟΛ.1029/1995 | Without VAT - POL.1029/1995 |
| 26 | `VatExemption::POL_1167_2015` | Χωρίς ΦΠΑ - ΠΟΛ.1167/2015 | Without VAT - POL.1167/2015 |
| 27 | `VatExemption::OTHER_EXEMPTIONS` | Λοιπές Εξαιρέσεις ΦΠΑ | Other VAT exemptions |
| 28 | `VatExemption::ARTICLE_29_TAX_FREE` | Χωρίς ΦΠΑ – άρθρο 29 περ. β’ παρ.1 του Κώδικα ΦΠΑ, (Tax Free) | Without VAT - art. 29 par. 1(b) of the VAT Code (Tax Free) |
| 29 | `VatExemption::ARTICLE_56_OSS_NON_UNION` | Χωρίς ΦΠΑ – άρθρο 56 του Κώδικα ΦΠΑ (OSS_μη ενωσιακό καθεστώς) | Without VAT - art. 56 of the VAT Code (OSS non-Union scheme) |
| 30 | `VatExemption::ARTICLE_57_OSS_UNION` | Χωρίς ΦΠΑ – άρθρο 57 του Κώδικα ΦΠΑ (OSS_ενωσιακό καθεστώς) | Without VAT - art. 57 of the VAT Code (OSS Union scheme) |
| 31 | `VatExemption::ARTICLE_58_IOSS` | Χωρίς ΦΠΑ – άρθρο 58 του Κώδικα ΦΠΑ (IOSS) | Without VAT - art. 58 of the VAT Code (IOSS) |
