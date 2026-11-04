# Upgrade guide for AI assistants: firebed/aade-mydata 5.x → 6.0

This page is written for coding assistants (Claude, Copilot, Cursor, ChatGPT, …) that
upgrade an application from `firebed/aade-mydata` 5.x to 6.0. It is exhaustive and
mechanical on purpose: every rename, every removed API and every behaviour change is
listed with a find/replace rule or a before/after snippet. Apply the rules in order,
then run the verification steps at the end. The human-oriented guide is
[upgrade-guide](./upgrade-guide).

## 0. Requirements

| | 5.x | 6.0 |
|---|---|---|
| PHP | ^8.1 | **^8.3** |
| guzzlehttp/guzzle | ^7.0.1 | ^7.8 \|\| ^8.0 (Guzzle 8 is supported; 7.x stays supported until the next major) |
| myDATA API | v2.0.2 | v2.0.2 (unchanged, XML output is identical) |

```json
{ "require": { "firebed/aade-mydata": "^6.0" } }
```

## 1. Renamed symbols (search and replace, whole-word)

| 5.x | 6.0 | Notes |
|---|---|---|
| `Firebed\AadeMyData\Models\Type` | `Firebed\AadeMyData\Models\Model` | base class of every data type |
| `Firebed\AadeMyData\Models\TypeArray` | `Firebed\AadeMyData\Models\ModelCollection` | base class of every list wrapper (`InvoicesDoc`, `PaymentMethods`, `TaxesTotals`, `Errors`, …) |
| `Firebed\AadeMyData\Enums\PaymentMethod` | `Firebed\AadeMyData\Enums\PaymentType` | the enum with values 1–8. The **model** `Firebed\AadeMyData\Models\PaymentMethod` keeps its name |
| `Firebed\AadeMyData\Traits\HasIterator` | removed | see §3 |
| `Firebed\AadeMyData\Models\TransportDetail` | removed | dropped by myDATA in v2.0.1 |
| `XMLWriter::buildType()` | `XMLWriter::buildModel()` | only matters if you extend the writers |
| `XMLReader::createType()` | `XMLReader::createModel()` | only matters if you extend the readers |
| docs folder `docs/types` | `docs/models` | links |

Enum **case names** became descriptive and the old code-like names are gone (`PaymentType::CASH`
instead of `PaymentType::METHOD_3`, `TaxType::WITHHELD_TAX` instead of `TaxType::TYPE_1`, `VatCategory::RATE_24` instead of `VatCategory::VAT_1`, …).
Rename every reference with the table in §1a; the backing values are unchanged, so code that passes
the raw values (`setVatCategory(1)`, `setInvoiceType('1.1')`) needs no change. `CountryCode`, `CurrencyCode`,
`TransmissionFailure`, the digital-goods-movement enums, the statement enums and the income/expenses
classification enums are unchanged. `InvoiceType` keeps its code-like names (`InvoiceType::TYPE_1_1`, …).

## 1a. Enum case renames (search and replace, whole-word)

| 5.x | 6.0 |
|---|---|
| `PaymentType::METHOD_1` | `PaymentType::DOMESTIC_PAYMENTS_ACCOUNT` |
| `PaymentType::METHOD_2` | `PaymentType::FOREIGN_PAYMENTS_ACCOUNT` |
| `PaymentType::METHOD_3` | `PaymentType::CASH` |
| `PaymentType::METHOD_4` | `PaymentType::CHEQUE` |
| `PaymentType::METHOD_5` | `PaymentType::ON_CREDIT` |
| `PaymentType::METHOD_6` | `PaymentType::WEB_BANKING` |
| `PaymentType::METHOD_7` | `PaymentType::POS` |
| `PaymentType::METHOD_8` | `PaymentType::IRIS` |
| `VatCategory::VAT_1` | `VatCategory::RATE_24` |
| `VatCategory::VAT_2` | `VatCategory::RATE_13` |
| `VatCategory::VAT_3` | `VatCategory::RATE_6` |
| `VatCategory::VAT_4` | `VatCategory::RATE_17` |
| `VatCategory::VAT_5` | `VatCategory::RATE_9` |
| `VatCategory::VAT_6` | `VatCategory::RATE_4` |
| `VatCategory::VAT_7` | `VatCategory::EXEMPT` |
| `VatCategory::VAT_8` | `VatCategory::WITHOUT_VAT` |
| `VatCategory::VAT_9` | `VatCategory::RATE_3_ARTICLE_31` |
| `VatCategory::VAT_10` | `VatCategory::RATE_4_ARTICLE_31` |
| `VatExemption::TYPE_1` | `VatExemption::ARTICLE_2_AND_3` |
| `VatExemption::TYPE_2` | `VatExemption::ARTICLE_5` |
| `VatExemption::TYPE_3` | `VatExemption::ARTICLE_17` |
| `VatExemption::TYPE_4` | `VatExemption::ARTICLE_18` |
| `VatExemption::TYPE_5` | `VatExemption::ARTICLE_21` |
| `VatExemption::TYPE_6` | `VatExemption::ARTICLE_24` |
| `VatExemption::TYPE_7` | `VatExemption::ARTICLE_27` |
| `VatExemption::TYPE_8` | `VatExemption::ARTICLE_29` |
| `VatExemption::TYPE_9` | `VatExemption::ARTICLE_30` |
| `VatExemption::TYPE_10` | `VatExemption::ARTICLE_31` |
| `VatExemption::TYPE_11` | `VatExemption::ARTICLE_32` |
| `VatExemption::TYPE_12` | `VatExemption::ARTICLE_32_OPEN_SEA_VESSELS` |
| `VatExemption::TYPE_13` | `VatExemption::ARTICLE_32_PAR_1_C_OPEN_SEA_VESSELS` |
| `VatExemption::TYPE_14` | `VatExemption::ARTICLE_33` |
| `VatExemption::TYPE_15` | `VatExemption::ARTICLE_44` |
| `VatExemption::TYPE_16` | `VatExemption::ARTICLE_45` |
| `VatExemption::TYPE_17` | `VatExemption::ARTICLE_47` |
| `VatExemption::TYPE_18` | `VatExemption::ARTICLE_48` |
| `VatExemption::TYPE_19` | `VatExemption::ARTICLE_54` |
| `VatExemption::TYPE_20` | `VatExemption::VAT_INCLUDED_ARTICLE_50` |
| `VatExemption::TYPE_21` | `VatExemption::VAT_INCLUDED_ARTICLE_51` |
| `VatExemption::TYPE_22` | `VatExemption::VAT_INCLUDED_ARTICLE_52` |
| `VatExemption::TYPE_23` | `VatExemption::VAT_INCLUDED_ARTICLE_53` |
| `VatExemption::TYPE_24` | `VatExemption::ARTICLE_8` |
| `VatExemption::TYPE_25` | `VatExemption::POL_1029_1995` |
| `VatExemption::TYPE_26` | `VatExemption::POL_1167_2015` |
| `VatExemption::TYPE_27` | `VatExemption::OTHER_EXEMPTIONS` |
| `VatExemption::TYPE_28` | `VatExemption::ARTICLE_29_TAX_FREE` |
| `VatExemption::TYPE_29` | `VatExemption::ARTICLE_56_OSS_NON_UNION` |
| `VatExemption::TYPE_30` | `VatExemption::ARTICLE_57_OSS_UNION` |
| `VatExemption::TYPE_31` | `VatExemption::ARTICLE_58_IOSS` |
| `WithheldPercentCategory::TAX_1` | `WithheldPercentCategory::INTEREST_15` |
| `WithheldPercentCategory::TAX_2` | `WithheldPercentCategory::ROYALTIES_20` |
| `WithheldPercentCategory::TAX_3` | `WithheldPercentCategory::MANAGEMENT_CONSULTING_FEES_20` |
| `WithheldPercentCategory::TAX_4` | `WithheldPercentCategory::TECHNICAL_WORKS_3` |
| `WithheldPercentCategory::TAX_5` | `WithheldPercentCategory::LIQUID_FUELS_AND_TOBACCO_1` |
| `WithheldPercentCategory::TAX_6` | `WithheldPercentCategory::OTHER_GOODS_4` |
| `WithheldPercentCategory::TAX_7` | `WithheldPercentCategory::SERVICES_8` |
| `WithheldPercentCategory::TAX_8` | `WithheldPercentCategory::ARCHITECTS_ENGINEERS_STUDIES_4` |
| `WithheldPercentCategory::TAX_9` | `WithheldPercentCategory::ARCHITECTS_ENGINEERS_OTHER_WORKS_10` |
| `WithheldPercentCategory::TAX_10` | `WithheldPercentCategory::LAWYERS_FEES_15` |
| `WithheldPercentCategory::TAX_11` | `WithheldPercentCategory::EMPLOYMENT_INCOME` |
| `WithheldPercentCategory::TAX_12` | `WithheldPercentCategory::MERCHANT_NAVY_OFFICERS_15` |
| `WithheldPercentCategory::TAX_13` | `WithheldPercentCategory::MERCHANT_NAVY_LOWER_CREW_10` |
| `WithheldPercentCategory::TAX_14` | `WithheldPercentCategory::SPECIAL_SOLIDARITY_CONTRIBUTION` |
| `WithheldPercentCategory::TAX_15` | `WithheldPercentCategory::EMPLOYMENT_TERMINATION_COMPENSATION` |
| `WithheldPercentCategory::TAX_16` | `WithheldPercentCategory::DOUBLE_TAXATION_TREATIES` |
| `WithheldPercentCategory::TAX_17` | `WithheldPercentCategory::OTHER_WITHHOLDINGS` |
| `WithheldPercentCategory::TAX_18` | `WithheldPercentCategory::DIVIDENDS_5` |
| `FeesPercentCategory::TYPE_1` | `FeesPercentCategory::MONTHLY_BILL_UP_TO_50_EUR` |
| `FeesPercentCategory::TYPE_2` | `FeesPercentCategory::MONTHLY_BILL_50_TO_100_EUR` |
| `FeesPercentCategory::TYPE_3` | `FeesPercentCategory::MONTHLY_BILL_100_TO_150_EUR` |
| `FeesPercentCategory::TYPE_4` | `FeesPercentCategory::MONTHLY_BILL_OVER_150_EUR` |
| `FeesPercentCategory::TYPE_5` | `FeesPercentCategory::PREPAID_MOBILE_AIRTIME_12` |
| `FeesPercentCategory::TYPE_6` | `FeesPercentCategory::SUBSCRIPTION_TV_10` |
| `FeesPercentCategory::TYPE_7` | `FeesPercentCategory::FIXED_TELEPHONY_5` |
| `FeesPercentCategory::TYPE_8` | `FeesPercentCategory::PLASTIC_BAG_ENVIRONMENTAL_FEE` |
| `FeesPercentCategory::TYPE_9` | `FeesPercentCategory::OLIVE_FLY_CONTROL_2` |
| `FeesPercentCategory::TYPE_10` | `FeesPercentCategory::OTHER_FEES` |
| `FeesPercentCategory::TYPE_11` | `FeesPercentCategory::OTHER_TAXES_FEES` |
| `FeesPercentCategory::TYPE_12` | `FeesPercentCategory::OLIVE_FLY_CONTROL_AMOUNT` |
| `FeesPercentCategory::TYPE_13` | `FeesPercentCategory::MONTHLY_BILL_PER_CONNECTION_10` |
| `FeesPercentCategory::TYPE_14` | `FeesPercentCategory::PREPAID_MOBILE_AIRTIME_10` |
| `FeesPercentCategory::TYPE_15` | `FeesPercentCategory::MOBILE_AGES_15_TO_29` |
| `FeesPercentCategory::TYPE_16` | `FeesPercentCategory::PLASTIC_PRODUCTS_ENVIRONMENTAL_LEVY` |
| `FeesPercentCategory::TYPE_17` | `FeesPercentCategory::RECYCLING_FEE` |
| `FeesPercentCategory::TYPE_18` | `FeesPercentCategory::TRANSIENT_STAY_FEE` |
| `FeesPercentCategory::TYPE_19` | `FeesPercentCategory::RESTAURANTS_GROSS_REVENUE_FEE` |
| `FeesPercentCategory::TYPE_20` | `FeesPercentCategory::ENTERTAINMENT_VENUES_GROSS_REVENUE_FEE` |
| `FeesPercentCategory::TYPE_21` | `FeesPercentCategory::CASINOS_GROSS_REVENUE_FEE` |
| `FeesPercentCategory::TYPE_22` | `FeesPercentCategory::OTHER_GROSS_REVENUE_FEES` |
| `OtherTaxesPercentCategory::TAX_1` | `OtherTaxesPercentCategory::FIRE_INSURANCE_PREMIUMS_A1` |
| `OtherTaxesPercentCategory::TAX_2` | `OtherTaxesPercentCategory::FIRE_INSURANCE_PREMIUMS_A2` |
| `OtherTaxesPercentCategory::TAX_3` | `OtherTaxesPercentCategory::LIFE_INSURANCE_PREMIUMS_4` |
| `OtherTaxesPercentCategory::TAX_4` | `OtherTaxesPercentCategory::OTHER_INSURANCE_PREMIUMS_15` |
| `OtherTaxesPercentCategory::TAX_5` | `OtherTaxesPercentCategory::EXEMPT_INSURANCE_PREMIUMS` |
| `OtherTaxesPercentCategory::TAX_6` | `OtherTaxesPercentCategory::HOTELS_1_2_STARS_0_50_EUR` |
| `OtherTaxesPercentCategory::TAX_7` | `OtherTaxesPercentCategory::HOTELS_3_STARS_1_50_EUR` |
| `OtherTaxesPercentCategory::TAX_8` | `OtherTaxesPercentCategory::HOTELS_4_STARS_3_EUR` |
| `OtherTaxesPercentCategory::TAX_9` | `OtherTaxesPercentCategory::HOTELS_5_STARS_4_EUR` |
| `OtherTaxesPercentCategory::TAX_10` | `OtherTaxesPercentCategory::FURNISHED_ROOMS_0_50_EUR` |
| `OtherTaxesPercentCategory::TAX_11` | `OtherTaxesPercentCategory::TV_ADVERTISING_TAX_5` |
| `OtherTaxesPercentCategory::TAX_12` | `OtherTaxesPercentCategory::LUXURY_TAX_IMPORTED_GOODS_10` |
| `OtherTaxesPercentCategory::TAX_13` | `OtherTaxesPercentCategory::LUXURY_TAX_DOMESTIC_GOODS_10` |
| `OtherTaxesPercentCategory::TAX_14` | `OtherTaxesPercentCategory::CASINO_TICKETS_STATE_DUTY_80` |
| `OtherTaxesPercentCategory::TAX_15` | `OtherTaxesPercentCategory::FIRE_INSURANCE_PREMIUMS_20` |
| `OtherTaxesPercentCategory::TAX_16` | `OtherTaxesPercentCategory::CUSTOMS_DUTIES` |
| `OtherTaxesPercentCategory::TAX_17` | `OtherTaxesPercentCategory::OTHER_TAXES` |
| `OtherTaxesPercentCategory::TAX_18` | `OtherTaxesPercentCategory::OTHER_TAXES_SURCHARGES` |
| `OtherTaxesPercentCategory::TAX_19` | `OtherTaxesPercentCategory::EXCISE_DUTY` |
| `OtherTaxesPercentCategory::TAX_20` | `OtherTaxesPercentCategory::HOTELS_1_2_STARS_1_50_EUR` |
| `OtherTaxesPercentCategory::TAX_21` | `OtherTaxesPercentCategory::HOTELS_3_STARS_3_EUR` |
| `OtherTaxesPercentCategory::TAX_22` | `OtherTaxesPercentCategory::HOTELS_4_STARS_7_EUR` |
| `OtherTaxesPercentCategory::TAX_23` | `OtherTaxesPercentCategory::HOTELS_5_STARS_10_EUR` |
| `OtherTaxesPercentCategory::TAX_24` | `OtherTaxesPercentCategory::FURNISHED_ROOMS_1_50_EUR` |
| `OtherTaxesPercentCategory::TAX_25` | `OtherTaxesPercentCategory::SHORT_TERM_RENTALS_1_50_EUR` |
| `OtherTaxesPercentCategory::TAX_26` | `OtherTaxesPercentCategory::SHORT_TERM_RENTAL_HOUSES_OVER_80_SQM_10_EUR` |
| `OtherTaxesPercentCategory::TAX_27` | `OtherTaxesPercentCategory::SELF_CATERING_VILLAS_10_EUR` |
| `OtherTaxesPercentCategory::TAX_28` | `OtherTaxesPercentCategory::SHORT_TERM_RENTALS_0_50_EUR` |
| `OtherTaxesPercentCategory::TAX_29` | `OtherTaxesPercentCategory::SHORT_TERM_RENTAL_HOUSES_OVER_80_SQM_4_EUR` |
| `OtherTaxesPercentCategory::TAX_30` | `OtherTaxesPercentCategory::SELF_CATERING_VILLAS_4_EUR` |
| `StampCategory::TYPE_1` | `StampCategory::RATE_1_2` |
| `StampCategory::TYPE_2` | `StampCategory::RATE_2_4` |
| `StampCategory::TYPE_3` | `StampCategory::RATE_3_6` |
| `StampCategory::TYPE_4` | `StampCategory::OTHER_CASES` |
| `TaxType::TYPE_1` | `TaxType::WITHHELD_TAX` |
| `TaxType::TYPE_2` | `TaxType::FEES` |
| `TaxType::TYPE_3` | `TaxType::OTHER_TAXES` |
| `TaxType::TYPE_4` | `TaxType::STAMP_DUTY` |
| `TaxType::TYPE_5` | `TaxType::DEDUCTIONS` |
| `UnitMeasurement::UNIT_1` | `UnitMeasurement::PIECES` |
| `UnitMeasurement::UNIT_2` | `UnitMeasurement::KILOGRAMS` |
| `UnitMeasurement::UNIT_3` | `UnitMeasurement::LITERS` |
| `UnitMeasurement::UNIT_4` | `UnitMeasurement::METERS` |
| `UnitMeasurement::UNIT_5` | `UnitMeasurement::SQUARE_METERS` |
| `UnitMeasurement::UNIT_6` | `UnitMeasurement::CUBIC_METERS` |
| `UnitMeasurement::UNIT_7` | `UnitMeasurement::PIECES_OTHER_CASES` |
| `MovePurpose::TYPE_1` | `MovePurpose::SALE` |
| `MovePurpose::TYPE_2` | `MovePurpose::SALE_FOR_THIRD_PARTIES` |
| `MovePurpose::TYPE_3` | `MovePurpose::SAMPLING` |
| `MovePurpose::TYPE_4` | `MovePurpose::EXHIBITION` |
| `MovePurpose::TYPE_5` | `MovePurpose::RETURN` |
| `MovePurpose::TYPE_6` | `MovePurpose::SAFEKEEPING` |
| `MovePurpose::TYPE_7` | `MovePurpose::PROCESSING_ASSEMBLY_DISASSEMBLY` |
| `MovePurpose::TYPE_8` | `MovePurpose::INTERNAL_MOVEMENT` |
| `MovePurpose::TYPE_9` | `MovePurpose::PURCHASE` |
| `MovePurpose::TYPE_10` | `MovePurpose::SHIP_AND_AIRCRAFT_SUPPLY` |
| `MovePurpose::TYPE_11` | `MovePurpose::FREE_DISPOSAL` |
| `MovePurpose::TYPE_12` | `MovePurpose::WARRANTY` |
| `MovePurpose::TYPE_13` | `MovePurpose::LOAN_FOR_USE` |
| `MovePurpose::TYPE_14` | `MovePurpose::STORAGE_AT_THIRD_PARTIES` |
| `MovePurpose::TYPE_15` | `MovePurpose::RETURN_FROM_SAFEKEEPING` |
| `MovePurpose::TYPE_16` | `MovePurpose::RECYCLING` |
| `MovePurpose::TYPE_17` | `MovePurpose::SCRAP_DESTRUCTION` |
| `MovePurpose::TYPE_18` | `MovePurpose::FIXED_ASSETS_MOVEMENT` |
| `MovePurpose::TYPE_19` | `MovePurpose::OTHER_MOVEMENTS` |
| `MovePurpose::TYPE_20` | `MovePurpose::TRANSPORT_AND_COURIER` |
| `SpecialInvoiceCategory::TYPE_1` | `SpecialInvoiceCategory::SUBSIDIES_GRANTS` |
| `SpecialInvoiceCategory::TYPE_2` | `SpecialInvoiceCategory::HOTEL_RETAIL_INCOME` |
| `SpecialInvoiceCategory::TYPE_3` | `SpecialInvoiceCategory::ACCOUNTING_ENTRY` |
| `SpecialInvoiceCategory::TYPE_4` | `SpecialInvoiceCategory::TAX_FREE` |
| `SpecialInvoiceCategory::TYPE_5` | `SpecialInvoiceCategory::COMPLEX_DOMESTIC_FOREIGN_TRANSACTIONS` |
| `SpecialInvoiceCategory::TYPE_6` | `SpecialInvoiceCategory::DECISION_139818_BENEFICIARIES` |
| `SpecialInvoiceCategory::TYPE_7` | `SpecialInvoiceCategory::AGRICULTURAL_PURCHASES_ARTICLE_41` |
| `SpecialInvoiceCategory::TYPE_8` | `SpecialInvoiceCategory::RETAIL_INCOME_FISCAL_DEVICE_AADE_1` |
| `SpecialInvoiceCategory::TYPE_9` | `SpecialInvoiceCategory::RETAIL_INCOME_FISCAL_DEVICE_AADE_2` |
| `SpecialInvoiceCategory::TYPE_10` | `SpecialInvoiceCategory::RETAIL_INCOME_FISCAL_DEVICE_DEVIATION` |
| `SpecialInvoiceCategory::TYPE_11` | `SpecialInvoiceCategory::HEATING_ALLOWANCE` |
| `SpecialInvoiceCategory::TYPE_12` | `SpecialInvoiceCategory::RESTAURANT_TRANSACTIONS` |
| `SpecialInvoiceCategory::TYPE_13` | `SpecialInvoiceCategory::CORRELATION_DIFFICULTY` |
| `InvoiceVariationType::TYPE_1` | `InvoiceVariationType::OMISSION_BY_RECIPIENT` |
| `InvoiceVariationType::TYPE_2` | `InvoiceVariationType::OMISSION_BY_ISSUER` |
| `InvoiceVariationType::TYPE_3` | `InvoiceVariationType::DEVIATION_BY_RECIPIENT` |
| `InvoiceVariationType::TYPE_4` | `InvoiceVariationType::DEVIATION_BY_ISSUER` |
| `RecType::TYPE_1` | `RecType::WITHHELD_TAXES_LINE` |
| `RecType::TYPE_2` | `RecType::FEES_LINE_WITH_VAT` |
| `RecType::TYPE_3` | `RecType::OTHER_TAXES_LINE_WITH_VAT` |
| `RecType::TYPE_4` | `RecType::STAMP_DUTY_LINE` |
| `RecType::TYPE_5` | `RecType::DEDUCTIONS_LINE` |
| `RecType::TYPE_6` | `RecType::GIFT_VOUCHER` |
| `RecType::TYPE_7` | `RecType::NEGATIVE_VALUES` |
| `EntityTypes::TYPE_1` | `EntityTypes::TAX_REPRESENTATIVE` |
| `EntityTypes::TYPE_2` | `EntityTypes::INTERMEDIARY` |
| `EntityTypes::TYPE_3` | `EntityTypes::CARRIER` |
| `EntityTypes::TYPE_4` | `EntityTypes::RECIPIENT_OF_SENDER` |
| `EntityTypes::TYPE_5` | `EntityTypes::SENDER` |
| `EntityTypes::TYPE_6` | `EntityTypes::OTHER_RELATED_ENTITIES` |
| `InvoiceDetailType::TYPE_1` | `InvoiceDetailType::THIRD_PARTY_SALES_SETTLEMENT` |
| `InvoiceDetailType::TYPE_2` | `InvoiceDetailType::THIRD_PARTY_SALES_FEE` |
| `FuelCode::TYPE_10` | `FuelCode::BENZINE_95_RON` |
| `FuelCode::TYPE_11` | `FuelCode::BENZINE_95_RON_PLUS` |
| `FuelCode::TYPE_12` | `FuelCode::BENZINE_100_RON` |
| `FuelCode::TYPE_13` | `FuelCode::BENZINE_LRP` |
| `FuelCode::TYPE_14` | `FuelCode::AVIATION_GASOLINE` |
| `FuelCode::TYPE_15` | `FuelCode::JET_FUEL` |
| `FuelCode::TYPE_20` | `FuelCode::DIESEL` |
| `FuelCode::TYPE_21` | `FuelCode::DIESEL_PREMIUM` |
| `FuelCode::TYPE_30` | `FuelCode::DIESEL_HEATING` |
| `FuelCode::TYPE_31` | `FuelCode::DIESEL_HEATING_PREMIUM` |
| `FuelCode::TYPE_32` | `FuelCode::DIESEL_LIGHT` |
| `FuelCode::TYPE_33` | `FuelCode::DIESEL_OTHER_USES` |
| `FuelCode::TYPE_34` | `FuelCode::DIESEL_MARINE` |
| `FuelCode::TYPE_35` | `FuelCode::KEROSENE_JP1` |
| `FuelCode::TYPE_36` | `FuelCode::KEROSENE_OTHER_USES` |
| `FuelCode::TYPE_37` | `FuelCode::FUEL_OIL` |
| `FuelCode::TYPE_38` | `FuelCode::FUEL_OIL_MARINE` |
| `FuelCode::TYPE_40` | `FuelCode::LPG` |
| `FuelCode::TYPE_41` | `FuelCode::LPG_METHANE_ENGINES_BULK` |
| `FuelCode::TYPE_42` | `FuelCode::LPG_METHANE_HEATING_BULK` |
| `FuelCode::TYPE_43` | `FuelCode::LPG_METHANE_ENGINES_BOTTLED` |
| `FuelCode::TYPE_44` | `FuelCode::LPG_METHANE_HEATING_BOTTLED` |
| `FuelCode::TYPE_50` | `FuelCode::CNG` |
| `FuelCode::TYPE_60` | `FuelCode::AROMATIC_HYDROCARBONS_2707` |
| `FuelCode::TYPE_61` | `FuelCode::CYCLIC_HYDROCARBONS_2902` |
| `FuelCode::TYPE_70` | `FuelCode::WHITE_SPIRIT` |
| `FuelCode::TYPE_71` | `FuelCode::LIGHT_OILS` |
| `FuelCode::TYPE_72` | `FuelCode::BIODIESEL` |
| `FuelCode::TYPE_999` | `FuelCode::OTHER_SERVICE_CHARGES` |

## 2. Model casts: `$casts` property → `casts()` method, lists via `Arr::of()`

This only affects code that **extends** the package models or defines its own. The
public getters/setters of the bundled models are unchanged.

Before (5.x):

```php
class MyModel extends Type
{
    protected array $casts = [
        'header' => InvoiceHeader::class,
        'rows'   => InvoiceDetails::class,   // a list, but nothing said so
    ];

    public function set($key, $value): static   // needed to collect repeated XML elements
    {
        if ($key === 'rows' && ! is_array($value)) {
            return $this->push($key, $value);
        }

        return parent::set($key, $value);
    }
}
```

After (6.0):

```php
use Firebed\AadeMyData\Casts\Arr;
use Firebed\AadeMyData\Models\Model;

class MyModel extends Model
{
    protected function casts(): array
    {
        return [
            'header' => InvoiceHeader::class,
            'rows'   => Arr::of(InvoiceDetails::class),   // list: single values are appended
            'marks'  => Arr::of('int'),                   // list of scalars
        ];
    }
    // no set() override
}
```

Rules:

- Replace `protected array $casts = [...]` with `protected function casts(): array { return [...]; }`.
- Wrap the cast of every attribute that holds several values in `Arr::of(...)`. Accepted types: a `Model` class, a `ModelCollection` class, a backed enum class, or `'int'`, `'float'`, `'string'`, `'bool'` (also as `Cast::INT`, `Cast::FLOAT`, `Cast::STRING`, `Cast::BOOL`).
- Delete every `set($key, $value)` override whose only job was `push()`-ing single values into a list. The base `Model::set()` now does that for `Arr::of()` attributes.
- Scalar attributes without a declared cast are cast from the getter's return type: `getIndex(): ?int` makes `set('index', '12')` store `12`. Declare a cast only when there is no getter.
- `ModelCollection` subclasses no longer take the child key in the constructor; the one `Arr::of()` entry of `casts()` names it:

```php
// 5.x
class TaxesTotals extends TypeArray
{
    protected array $casts = ['taxes' => TaxTotals::class];
    public function __construct(array $taxes = []) { parent::__construct('taxes', $taxes); }
}

// 6.0
class TaxesTotals extends ModelCollection
{
    protected function casts(): array { return ['taxes' => Arr::of(TaxTotals::class)]; }
}
```

- Models whose constructor takes positional values (e.g. `new Location($lat, $lng)`) are built from attribute arrays with `Model::fromArray([...])`; `Model::make([...])` now does the same, so `Location::make(['latitude' => 40.1])` works.

## 3. Removed APIs

| Removed | Replacement |
|---|---|
| `Invoice::getOtherTransportDetails()`, `addOtherTransportDetail()`, `setOtherTransportDetails()` and the `TransportDetail` model | none (myDATA ≥ 2.0.1 has no `otherTransportDetails`). Delete the calls. |
| `InvoiceSummary::getTotalInformationalTaxAmount()`, `setTotalInformationalTaxAmount()` | none; the value never reached the XML. Delete the calls. |
| `WithheldPercentCategory::affectsTotalGrossValue()` | none |
| `RequestedBookInfo` as `ArrayAccess`/`Countable`/`IteratorAggregate` (trait `HasIterator`) | `$bookInfo->getBookInfo()` returns `BookInfo[]`; `count($bookInfo)` → `count($bookInfo->getBookInfo() ?? [])` |
| `Type::isEnum()` static enum cache (`Type::$enumCache`) | `Model::cast($key)?->isEnum()`; nothing to migrate unless you used reflection on it |
| `MyDataRequest` private statics `$user_id`, `$subscription_key`, `$env`, `$is_provider`, `$request_options`, `$handler`, `$gateway` | `MyDataRequest::defaultConnection()` (a `Connection`); only matters for code that reached them by reflection |
| `MyDataRequest::httpClient()` building a new Guzzle client per call | still exists, returns the default connection's cached client |
| Trait `Models\HasSchemaValidation` (`$this->validateSchema($xml, 'InvoicesDoc-v2.0.2.xsd')`) | `Xml\Schema::validate($xml, 'InvoicesDoc')` (name without the version) |
| `GuzzleGateway::fail()` (in a subclass) | override `GuzzleGateway::exception(GuzzleException, string $body, ?int $retryAfter): MyDataException`; return the exception instead of throwing it |
| `$request instanceof SendInvoices` meant to cover every invoice send (e.g. in a custom `Gateway`) | `$request instanceof InvoicesDocRequest` (SendInvoices and SendUnsignedInvoices); SendUnsignedInvoices no longer extends SendInvoices |
| `$request instanceof CancelInvoice` meant to cover the note cancellations | `$request instanceof CancellationRequest`; CancelDeliveryNote and CancelReceivingNote no longer extend CancelInvoice |
| `$this->ensureProvider()` / `$this->ensureERP()` in a custom request's `handle()` | declare `protected const ?Channel CHANNEL = Channel::Provider;` (or `Channel::Erp`; `use Firebed\AadeMyData\Http\Channel;`) and delete the call; the request is checked before it is sent and still throws `UnsupportedChannelException` |

## 4. Behaviour changes to check in your code

1. **Invalid enum values throw.** `set()`/setters with a value that is not a case of the declared enum (e.g. `setVatCategory(99)`, `setInvoiceType('wrong')`) throw `Firebed\AadeMyData\Exceptions\InvalidAttributeException` (an `InvalidArgumentException`). In 5.x the attribute silently became `null`. Reading myDATA XML is unaffected: readers run in `Model::lenient()` mode and unknown values read as `null` as before.
2. **Wrong types throw** for cast attributes: a string where a model is expected, an object where a scalar is expected, …, same exception.
3. **Scalars are typed after reading XML.** `getIndex()`, `getAmount()`, `isSelfPricing()` … return `int`/`float`/`bool` for values read from XML, and `toArray()`/`toJson()` contain typed values instead of strings. Code that compared with `===` against strings (`$response->getIndex() === '1'`) must compare against ints.
4. **`''` means "no value"** on every cast attribute: `setClassificationType('')` stores `null`, an empty XML element `<counterpart/>` reads as `null`, an empty wrapper `<receptionEmails/>` reads as an empty collection.
5. **`Invoice::getTaxesTotals()` returns `null`** when no taxes totals were added. 5.x returned a fresh empty `TaxesTotals` that was not attached to the invoice. Use `?->`, or `addTaxesTotals()` which still creates the wrapper.
6. **Empty wrapper elements are not written.** An empty `PaymentMethods`, `TaxesTotals` or `PackingsDeclaration` produces no `<paymentMethods/>`-style element in the XML (which the XSD rejected anyway).
7. **Setting a single value on a list appends it; setting a list replaces it; setting `null` clears it.** `ModelCollection::set($key, $item)` appends (5.x replaced the list with `[$item]`).
8. **`TaxTotals::getTaxCategory()` returns `?int`**, the code of the category (which enum it belongs to depends on `taxType`). `setTaxCategory()` still accepts the enums and stores their value. In 5.x the getter returned the enum you had set, or failed on values read from XML.
9. **`HasLabels::labels()`** returns `[value => label, …]` instead of a list of single-entry arrays.
10. **Request XML is not pretty-printed by default** (`toXml()`, the body sent to myDATA). `getRequestXml()` and `getResponseXML()` still format on demand. Less bytes on the wire, same content.
11. **`Connection::make()` and `MyData::connect()` default to the sandbox** (`dev`); pass `'prod'` or `Environment::Production` explicitly.
12. **The library runs with `declare(strict_types=1)`.** This only affects calls made *inside* the library; your own files keep their own mode. Setters that commonly received ints were widened (`InvoiceHeader::setAa(string|int)`, `CancelInvoice::handle(string|int $mark, string|int|null $entityVatNumber)`).
13. **`Environment`**: `MyDataRequest::setEnvironment()`/`init()` accept `'dev'`/`'prod'` (any case) or the new `Firebed\AadeMyData\Http\Environment` enum; any other string throws `InvalidArgumentException` (5.x silently treated it as dev).
14. **`RequestDocs` / `RequestTransmittedDocs` are ERP-only.** On a provider connection (`asProvider()`, `init(..., true)`) they throw `UnsupportedChannelException`. Replace provider calls with `RequestProviderDocs::handle(string $issuerVat, string|int $mark = 0, ?string $nextPartitionKey = null, ?string $nextRowKey = null): RequestedProviderDoc` (or `MyData::requestProviderDocs()`). It returns `ProviderInvoice` entries (`getIssuerVAT()`, `getInvoiceProviderMark(): ?int`, `getInvoiceUid()`, `getAuthenticationCode()`), not invoices.

## 5. New APIs you may adopt

### Connection object and instance client (multi-tenant)

```php
use Firebed\AadeMyData\Http\Connection;
use Firebed\AadeMyData\MyData;

$mydata = MyData::connect($userId, $subscriptionKey, 'prod');          // or
$mydata = new MyData(Connection::make($userId, $subscriptionKey, 'prod')   // 'dev' when omitted
    ->asProvider()            // myDataProvider channel
    ->withTimeout(30)
    ->withConnectionTimeout(5)
    ->withVerify('/path/ca.pem')
    ->withRetries(3, 500)     // retry 429/502/503/504/connection failures, 500 ms doubling
    ->withGateway($gateway)); // optional custom Gateway

$response = $mydata->sendInvoices($invoice);
$xml      = $mydata->lastRequest()->getRequestXml();

// Any request can be bound to a connection without the client:
(new SendInvoices)->using($connection)->handle($invoice);
```

The static configuration (`MyDataRequest::init()`, `setCredentials()`, `setEnvironment()`,
`verifyClient()`, `setTimeout()`, `setConnectionTimeout()`, `setRequestOptions()`, `setGateway()`,
`setHandler()`) still works and configures `MyDataRequest::defaultConnection()`, which every
request without `using()` uses. New: `MyDataRequest::setRetries(int $retries, int $delayMs)`.

### Rate limits

myDATA throttles a subscription key by calls per ~5-minute window (sandbox: `RequestDocs` + `RequestTransmittedDocs`
share 11 calls per 300 s window; other operations have their own counters; production limits unpublished). A 429 carries
`Retry-After` (seconds). The library: retries a 429 only when the wait is ≤ 10 s; otherwise throws
`RateLimitExceededException` with `getRetryAfter()` / `getRetryAt()`. `Connection::withThrottle(int $ms)` spaces requests;
`Connection::withRateLimitWait(int $maxSeconds = 600)` sits out the wait and resends. Static: `MyDataRequest::setThrottle()`,
`MyDataRequest::setRateLimitWait()`. Pagination generators accept `nextPartitionKey` / `nextRowKey` to resume.

### Automatic pagination

```php
foreach ((new RequestDocs)->pages(mark: '0', dateFrom: '01/01/2026', dateTo: '31/01/2026') as $page) {
    // $page is a RequestedDoc; the continuation token is followed automatically
}
foreach ((new RequestTransmittedDocs)->invoices('0') as $invoice) { /* every Invoice of every page */ }
(new RequestMyIncome)->pages($from, $to);   // RequestedBookInfo pages
(new RequestVatInfo)->pages($from, $to);    // RequestedVatInfo pages
(new RequestE3Info)->pages($from, $to);     // RequestedE3Info pages
```

### Provider channel

```php
$doc = $mydata->requestProviderDocs('123456789', mark: $lastMark);     // RequestedProviderDoc, provider only
$doc->getInvoices();                                                   // list<ProviderInvoice>
$doc->findByUid($uid);                                                 // every mark of a uid (2+ = registered twice)
(new RequestProviderDocs)->invoices('123456789', $lastMark);           // Generator over every page
$mydata->requestReceiverInfo('123456789')->getReceptionProviders()?->vatNumbers();
$mydata->sendUnsignedInvoices($invoices);                              // invoices under issue whose Provider's Signature was never
                                                                       // matched with an invoice and payment (Α.1155/2023 art. 4, 4A,
                                                                       // «Υπό Έκδοση»); a payment that did not complete is only recorded
                                                                       // (Α.1160/2025 art. 3 par. 2γ); answers a mark, no auth code

$response->getDuplicate();       // ?Duplicate{uid, mark: int, authenticationCode: ?string} from error 228
$response->getDuplicate()?->toResponse($index);   // the successful Response it stands for (no qrUrl)
$response->getReceptionProviders();               // ?ReceptionProviders
```

A provider that parses error 228 itself (regex on "It has already been sent for another invoice") can switch to
`getDuplicate()`. The authentication code is null while myDATA has not stored the invoice yet.

### Faking myDATA in tests

Replace hand-built Guzzle `MockHandler` stacks or custom test gateways in application tests with the fake:

```php
use Firebed\AadeMyData\MyData;
use Firebed\AadeMyData\Testing\FakeGateway;
use Firebed\AadeMyData\Testing\RecordedRequest;

$fake = MyData::fake([                                   // intercepts every request, any connection
    SendInvoices::class => FakeGateway::rejected(101, 'Invalid VAT number'),
    RequestDocs::class  => FakeGateway::sequence($page1, $page2),
    '*'                 => new RateLimitExceededException(retryAfter: 300),
]);
// no response given: sends/cancellations succeed (marks from 400000000000001, invoice uid), searches return nothing,
// delivery-note/statement calls throw LogicException until given one
$fake->assertSent(SendInvoices::class, fn (SendInvoices $r, RecordedRequest $sent) => $sent->count() === 1);
$fake->assertNotSent(CancelInvoice::class);
$fake->assertSentCount(1);
$fake->assertNothingSent();
MyData::stopFaking();                                    // in tearDown()
```

A class answers and asserts its own requests only (`SendInvoices::class` does not cover `SendUnsignedInvoices`, nor
`CancelInvoice::class` the note cancellations); an abstract parent (`MyDataGetRequest`, `RequestBookInfo`) covers its
children. The assertions call PHPUnit's `Assert` (`phpunit/phpunit` is suggested, not required).

Response values: XML string | model (`ResponseDoc`, `Response`, `RequestedDoc`, …) | `list<Response>` | `list<Invoice>`
(→ `RequestedDoc`) | `Throwable` (thrown) | `Closure(MyDataRequest, RecordedRequest)` returning any of these | `FakeGateway::sequence(...)`.

### Validation before sending

`Connection::withValidation()` (static: `MyDataRequest::setValidation()`) validates each document request against its
XSD before sending; failure throws `Exceptions\SchemaValidationException` (`getSchema()`, `getErrors()` as
`list<array{field: ?string, message: ?string}>`). Off by default. Applies to `SendInvoices`, `SendIncomeClassification`,
`SendExpensesClassification`, `SendPaymentsMethod`, `RegisterTransfer`, `ConfirmDeliveryOutcome`, `ConfirmDeliveryReturn`,
`RejectDeliveryNote`, `GenerateGroupQrCode`, `SendStatement`.

### Listeners and logging

```php
$connection = Connection::make($userId, $subscriptionKey, 'prod')
    ->withLogger($psrLogger, withBodies: false)                 // info / warning (retry, 429) / error; never credentials; without
                                                                // bodies: no XML, only safe query keys, redacted failure messages,
                                                                // context exception_class/exception_code instead of exception;
                                                                // replaces an earlier logger, null removes it
    ->afterSending(fn (Exchange $e) => ...)                     // once per request: action(), method, url, query,
                                                                // requestBody, responseBody, exception, duration (s), successful()
    ->beforeRetry(fn (Retry $r) => ...);                        // attempt, delay (ms), reason (MyDataException), rateLimited()
```

Static: `MyDataRequest::setLogger()`, `afterSending()`, `beforeRetry()`, `removeListener()`, `removeListeners()`.
Listeners add up, also run for faked requests, and are skipped when they throw (they never change the request's
outcome). A request rejected by `withValidation()` is never sent, so no listener sees it. `afterSending($l, key: 'audit')`
replaces the listener under that key (register once in code that boots repeatedly); `withoutListener('audit' | $l)`,
`withoutListeners()` remove them (the logger stays; `withLogger(null)` removes it).

### Models

- `Model::fromArray(array)`, `Model::toJson()`, `JsonSerializable` (`json_encode($invoice)`), `Model::unset($key)`, `Model::cast($key)`, `Model::lenient(fn () => ...)`.
- `ModelCollection::last()`, `map()`, `filter()`, `childKey()`, `$collection[] = $item`.

## 6. Verification

```shell
composer update firebed/aade-mydata
vendor/bin/phpunit            # your suite
grep -rn "Models\\\\Type\b\|TypeArray\|Enums\\\\PaymentMethod\b\|HasIterator\|TransportDetail\b\|InformationalTax\|affectsTotalGrossValue\|OtherTransportDetail" src app tests
grep -rnE "(PaymentType::METHOD_|VatCategory::VAT_|VatExemption::TYPE_|TaxType::TYPE_|WithheldPercentCategory::TAX_|OtherTaxesPercentCategory::TAX_|FeesPercentCategory::TYPE_|StampCategory::TYPE_|UnitMeasurement::UNIT_|MovePurpose::TYPE_|SpecialInvoiceCategory::TYPE_|InvoiceVariationType::TYPE_|RecType::TYPE_|EntityTypes::TYPE_|InvoiceDetailType::TYPE_|FuelCode::TYPE_)" src app tests
```

The grep must come back empty. Then search for `protected array $casts` in your own models and
for `function set($key` overrides, and migrate them as in §2. Finally run any flow that reads
myDATA responses and check comparisons against `getIndex()`, `getInvoiceMark()` and amounts (§4.3).
