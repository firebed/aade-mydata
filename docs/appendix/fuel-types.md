# Fuel codes

Fuel code of an invoice row (`fuelCode`). Enum: `FuelCode`.

```php
use Firebed\AadeMyData\Enums\FuelCode;
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Models\InvoiceDetails;

$row = (new InvoiceDetails())->setFuelCode(FuelCode::DIESEL);

// Can the invoice type carry fuel rows?
InvoiceType::TYPE_11_1->supportsFuelInvoice(); // true
```

| Code | Enum | `label()` | English |
|---|---|---|---|
| 10 | `FuelCode::BENZINE_95_RON` | Benzine 95RON | Gasoline 95 RON |
| 11 | `FuelCode::BENZINE_95_RON_PLUS` | Benzine 95RON+ | Gasoline 95 RON+ |
| 12 | `FuelCode::BENZINE_100_RON` | Benzine 100RON | Gasoline 100 RON |
| 13 | `FuelCode::BENZINE_LRP` | Benzine LRP | Gasoline LRP |
| 14 | `FuelCode::AVIATION_GASOLINE` | Βενζίνη αεροπλάνων | Aviation gasoline |
| 15 | `FuelCode::JET_FUEL` | Ειδικό καύσιμο αεριωθουμένων | Jet fuel |
| 20 | `FuelCode::DIESEL` | Diesel | Diesel |
| 21 | `FuelCode::DIESEL_PREMIUM` | Diesel premium | Diesel premium |
| 30 | `FuelCode::DIESEL_HEATING` | Diesel Heating | Heating diesel |
| 31 | `FuelCode::DIESEL_HEATING_PREMIUM` | Diesel Heating premium | Heating diesel premium |
| 32 | `FuelCode::DIESEL_LIGHT` | Diesel Light | Diesel light |
| 33 | `FuelCode::DIESEL_OTHER_USES` | Diesel άλλων χρήσεων | Diesel for other uses |
| 34 | `FuelCode::DIESEL_MARINE` | Diesel ναυτιλίας | Marine diesel |
| 35 | `FuelCode::KEROSENE_JP1` | Κηροζίνη JP1 | Kerosene JP1 |
| 36 | `FuelCode::KEROSENE_OTHER_USES` | Κηροζίνη άλλων χρήσεων | Kerosene for other uses |
| 37 | `FuelCode::FUEL_OIL` | Μαζούτ | Fuel oil |
| 38 | `FuelCode::FUEL_OIL_MARINE` | Μαζούτ ναυτιλίας | Marine fuel oil |
| 40 | `FuelCode::LPG` | LPG (υγραέριο) | LPG |
| 41 | `FuelCode::LPG_METHANE_ENGINES_BULK` | Υγραέριο (LPG) και μεθάνιο βιομηχανικό/εμπορικό κινητήρων (χύδην) | LPG and methane for industrial/commercial engines (bulk) |
| 42 | `FuelCode::LPG_METHANE_HEATING_BULK` | Υγραέριο (LPG) και μεθάνιο θέρμανσης και λοιπών χρήσεων (χύδην) | LPG and methane for heating and other uses (bulk) |
| 43 | `FuelCode::LPG_METHANE_ENGINES_BOTTLED` | Υγραέριο (LPG) και μεθάνιο βιομηχανικό/εμπορικό κινητήρων (σε φιάλες) | LPG and methane for industrial/commercial engines (bottled) |
| 44 | `FuelCode::LPG_METHANE_HEATING_BOTTLED` | Υγραέριο (LPG) και μεθάνιο θέρμανσης και λοιπών χρήσεων (σε φιάλες) | LPG and methane for heating and other uses (bottled) |
| 50 | `FuelCode::CNG` | CNG (πεπιεσμένο φυσικό αέριο) | CNG (compressed natural gas) |
| 60 | `FuelCode::AROMATIC_HYDROCARBONS_2707` | Αρωματικοί Υδρογονάνθρακες Δασμολογικής Κλάσης 2707 | Aromatic hydrocarbons, tariff heading 2707 |
| 61 | `FuelCode::CYCLIC_HYDROCARBONS_2902` | Κυκλικοί Υδρογονάνθρακες Δασμολογικής Κλάσης 2902 | Cyclic hydrocarbons, tariff heading 2902 |
| 70 | `FuelCode::WHITE_SPIRIT` | Ελαφρύ πετρέλαιο (WHITE SPIRIT) | Light petroleum (white spirit) |
| 71 | `FuelCode::LIGHT_OILS` | Ελαφριά λάδια | Light oils |
| 72 | `FuelCode::BIODIESEL` | Βιοντίζελ | Biodiesel |
| 999 | `FuelCode::OTHER_SERVICE_CHARGES` | Λοιπές χρεώσεις υπηρεσιών | Other service charges |

Use code `999` when a fuel invoice also bills other small charges besides fuel.
