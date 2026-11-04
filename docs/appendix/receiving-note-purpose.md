# Receiving note purposes <sub><sup>v2.0.2</sup></sub>

Reason for issuing a receiving note (`receivingNotePurpose`). Enum: `ReceivingNotePurpose`. Required for invoice types `10.1` and `10.2`.

```php
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Enums\ReceivingNotePurpose;
use Firebed\AadeMyData\Models\InvoiceHeader;

$header = (new InvoiceHeader())
    ->setInvoiceType(InvoiceType::TYPE_10_2)
    ->setReceivingNotePurpose(ReceivingNotePurpose::OTHER_CASES)
    ->setOtherReceivingNotePurposeTitle('Consignment return');
```

| Code | Enum | `label()` | English | Allowed for |
|---|---|---|---|---|
| 1 | `ReceivingNotePurpose::NOT_OBLIGED_TO_ISSUE` | ΜΗ ΥΠΟΧΡΕΟΣ ΕΚΔΟΣΗΣ | Not obliged to issue | 10.1, 10.2 |
| 2 | `ReceivingNotePurpose::REFUSAL_OR_INADVERTENT_NON_ISSUANCE` | ΑΡΝΗΣΗ ΕΚΔΟΣΗΣ / ΕΚ ΠΑΡΑΔΡΟΜΗΣ ΜΗ ΕΚΔΟΣΗ | Refusal to issue / not issued by mistake | 10.1, 10.2 |
| 3 | `ReceivingNotePurpose::INTRA_COMMUNITY_ACQUISITION` | ΕΝΔΟΚΟΙΝΟΤΙΚΗ ΑΠΟΚΤΗΣΗ | Intra-community acquisition | 10.1, 10.2 |
| 4 | `ReceivingNotePurpose::THIRD_COUNTRY_ACQUISITION` | ΑΠΟΚΤΗΣΗ ΤΡΙΤΗ ΧΩΡΑ | Third-country acquisition | 10.1, 10.2 |
| 5 | `ReceivingNotePurpose::QUANTITATIVE_CONTROL` | ΠΟΣΟΤΙΚΟΣ ΕΛΕΓΧΟΣ | Quantity check | 10.1 |
| 6 | `ReceivingNotePurpose::NON_OR_PARTIAL_DELIVERY` | ΜΗ / ΜΕΡΙΚΗ ΠΑΡΑΔΟΣΗ | No or partial delivery | 10.1, 10.2 |
| 7 | `ReceivingNotePurpose::OTHER_CASES` | ΛΟΙΠΕΣ ΠΕΡΙΠΤΩΣΕΙΣ | Other cases | 10.1, 10.2 |

> [!NOTE]
> With code `7` you must also set `otherReceivingNotePurposeTitle` (max 150 characters).
