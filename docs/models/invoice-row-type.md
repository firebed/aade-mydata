# InvoiceDetails

`InvoiceDetails` is one row of the invoice (myDATA type `InvoiceRowType`). Add it with `Invoice::addInvoiceDetails()`.

```php
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Enums\VatCategory;
use Firebed\AadeMyData\Models\InvoiceDetails;

$row = new InvoiceDetails();
$row->setLineNumber(1);
$row->setNetValue(100.00);
$row->setVatCategory(VatCategory::RATE_24);
$row->setVatAmount(24.00);
$row->addIncomeClassification(
    IncomeClassificationType::E3_561_001,
    IncomeClassificationCategory::CATEGORY_1_1,
    100.00,
);

$invoice->addInvoiceDetails($row);
```

`addIncomeClassification()` and `addExpensesClassification()` take either a model
([IncomeClassification](income-classification-type), [ExpensesClassification](expenses-classification-type))
or the type, category and amount.

Each field has a setter and a getter named after it (`netValue` → `setNetValue()`, `getNetValue()`). The table shows
other method names only when they differ.

| Field                        | Type                                                                          | Required | Description |
|------------------------------|-------------------------------------------------------------------------------|----------|-------------|
| lineNumber                   | `int`                                                                         | Yes      | Row number (min 1) |
| recType                      | [`RecType`](../appendix/rec-types)                                            | No       | Row type |
| TaricNo                      | `string`                                                                      | No       | Taric code (10 characters). Methods `setTaricNo()`, `getTaricNo()` |
| itemCode                     | `string`                                                                      | No       | Item code (max 50) |
| itemDescr                    | `string`                                                                      | No       | Item description (max 300) |
| fuelCode                     | [`FuelCode`](../appendix/fuel-types)                                          | No       | Fuel code |
| quantity                     | `float`                                                                       | No       | Quantity (min 0) |
| measurementUnit              | [`UnitMeasurement`](../appendix/measurement-units)                            | No       | Unit of measurement |
| invoiceDetailType            | [`InvoiceDetailType`](../appendix/invoice-detail-types)                       | No       | Marking |
| netValue                     | `float`                                                                       | Yes      | Net value |
| vatCategory                  | [`VatCategory`](../appendix/vat-categories)                                   | Yes      | VAT category |
| vatAmount                    | `float`                                                                       | Yes      | VAT amount |
| vatExemptionCategory         | [`VatExemption`](../appendix/vat-exemption-categories)                        | No       | VAT exemption reason |
| dienergia                    | [Ship](ship-type)                                                             | No       | Ship activity declaration (POL 1177/2018 art. 27) |
| discountOption               | `bool`                                                                        | No       | Right to discount. Getter `isDiscountOption()` |
| withheldAmount               | `float`                                                                       | No       | Withholding tax amount |
| withheldPercentCategory      | [`WithheldPercentCategory`](../appendix/withheld-percent-categories)          | No       | Withholding tax category |
| stampDutyAmount              | `float`                                                                       | No       | Stamp duty amount |
| stampDutyPercentCategory     | [`StampCategory`](../appendix/stamp-categories)                               | No       | Stamp duty category |
| feesAmount                   | `float`                                                                       | No       | Fees amount |
| feesPercentCategory          | [`FeesPercentCategory`](../appendix/fees-percent-categories)                  | No       | Fees category |
| otherTaxesPercentCategory    | [`OtherTaxesPercentCategory`](../appendix/other-taxes-percent-categories)     | No       | Other taxes category |
| otherTaxesAmount             | `float`                                                                       | No       | Other taxes amount |
| deductionsAmount             | `float`                                                                       | No       | Deductions amount |
| lineComments                 | `string`                                                                      | No       | Row comments (max 150) |
| incomeClassification         | [`IncomeClassification[]`](income-classification-type)                        | No       | Income classifications |
| expensesClassification       | [`ExpensesClassification[]`](expenses-classification-type)                    | No       | Expense classifications |
| quantity15                   | `float`                                                                       | No       | Quantity at 15 °C |
| otherMeasurementUnitQuantity | `int`                                                                         | No       | Count for unit "pieces, other cases" |
| otherMeasurementUnitTitle    | `string`                                                                      | No       | Title for unit "pieces, other cases" (max 150) |
| notVAT195                    | `bool`                                                                        | No       | Row does not count in the VAT return |
| movePurposeLine              | [`MovePurpose`](../appendix/move-purpose)                                     | No       | Purpose of the movement for this row |
| otherMovePurposeLineTitle    | `string`                                                                      | No       | Title of the other movement purpose for this row (max 150) |

The amount setters have `add...()` versions that add to the current value: `addNetValue()`, `addVatAmount()`,
`addWithheldAmount()`, `addStampDutyAmount()`, `addFeesAmount()`, `addOtherTaxesAmount()`, `addDeductionsAmount()`.

## Fields outside myDATA

These fields stay on the model (`toArray()`, `make()`) but never go into the XML. Gateways that send through an
e-invoicing provider read them.

| Method                                  | Description |
|-----------------------------------------|-------------|
| `setUnitPrice(float)`                   | Price per unit |
| `setDiscount(DiscountType, float)`      | Row discount. `DiscountType::PERCENTAGE` or `DiscountType::AMOUNT` |
| `setExtraFields(array)`, `addExtraField($key, $value)` | Free key/value pairs for the provider |

## Notes

- Always fill the amount field, whatever the category.
- `vatExemptionCategory` is required when `vatCategory` is a 0% category.
- For accounting entries without VAT, use `VatCategory::WITHOUT_VAT` (8).
- `lineComments` is free text for the tax office.
- The issuer sends income classifications and the recipient sends expense classifications with the invoice, in
  `incomeClassification` and `expensesClassification`.
- `recType`:
    - Rows with `RecType::FEES_LINE_WITH_VAT` (2) or `RecType::OTHER_TAXES_LINE_WITH_VAT` (3) carry the fee or tax
      in `netValue`. Leave `feesAmount` / `otherTaxesAmount` empty, and do not send any other tax on that row.
      With such rows you may still send withholding, fees, other taxes, stamp duty and deductions at invoice level.
    - `RecType::NEGATIVE_VALUES` (7) is allowed only on types `17.3`, `17.4`, `17.5` and `17.6`. The row values
      are negative, but you send them as positive numbers. The [InvoiceSummary](invoice-summary-type) sums the
      absolute values.
- `fuelCode` is allowed only on the provider channel and only for fuel invoices (`fuelInvoice = true`).
  Code `999` covers other charges on a fuel invoice. Only one row per invoice may use it, and its net value must
  not exceed the net value of the fuel rows.
- With `measurementUnit` = `UnitMeasurement::PIECES_OTHER_CASES` (7), fill `otherMeasurementUnitQuantity` and
  `otherMeasurementUnitTitle`, e.g. `3` and `Pallets`. `quantity` is still the number of items moved, not packages.
- `notVAT195 = true` keeps the row out of the VAT return (output). Allowed only on income invoices of types 1.1 to 11.5.
