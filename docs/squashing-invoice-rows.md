# Squashing invoice rows

E-invoicing providers and ERPs only have to send a summary of the invoice lines and their
classifications to myDATA, not every line. `squashInvoiceRows()` groups the lines and sums them for you.

Lines are grouped when they have the same:

- VAT category
- VAT exemption category
- withholding tax category
- fees category
- other taxes category
- stamp duty category
- `notVAT195`
- invoice detail type (`invoiceDetailType`)

Lines with a `recType` are not squashed. They stay as they are, after the squashed lines.

In each group, the amounts are summed (net value, VAT, withholding tax, fees, other taxes, stamp duty,
deductions) and rounded to 2 decimals. Income classifications with the same category and type are summed.
Expense classifications are summed when they also have the same VAT category and VAT exemption category.
The lines are numbered again from 1.

## Squash the rows

```php
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Enums\VatCategory;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoiceDetails;

$invoice = new Invoice();

$invoice->addInvoiceDetails(
    (new InvoiceDetails())
        ->setLineNumber(1)
        ->setNetValue(4.03)
        ->setVatCategory(VatCategory::RATE_24)
        ->setVatAmount(0.97)
        ->addIncomeClassification(IncomeClassificationType::E3_561_001, IncomeClassificationCategory::CATEGORY_1_1, 4.03)
);

foreach ([2, 3] as $line) {
    $invoice->addInvoiceDetails(
        (new InvoiceDetails())
            ->setLineNumber($line)
            ->setNetValue(4.03)
            ->setVatCategory(VatCategory::RATE_24)
            ->setVatAmount(0.97)
            ->addIncomeClassification(IncomeClassificationType::E3_561_001, IncomeClassificationCategory::CATEGORY_1_1, 2.02)
            ->addIncomeClassification(IncomeClassificationType::E3_561_007, IncomeClassificationCategory::CATEGORY_1_1, 2.01)
    );
}

$invoice->squashInvoiceRows();
$invoice->summarizeInvoice(); // after squashing

echo $invoice->toXml();
```

Call `summarizeInvoice()` after `squashInvoiceRows()`, so the summary matches the lines you send.

| Method | Does |
|---|---|
| `squashInvoiceRows($options = [])` | Replaces the lines with the squashed lines |
| `unSquashInvoiceRows()` | Puts the original lines back |
| `isSquashed()` | `true` after `squashInvoiceRows()` |

### Options

| Option | Default | Description |
|---|---|---|
| `clsLineNumber` | `false` | `true` adds an `id` to each classification of a squashed line (1, 2, ...) |
| `vatAmountTolerance` | `null` | See below |

```php
$invoice->squashInvoiceRows(['clsLineNumber' => true, 'vatAmountTolerance' => 1.00]);
```

### VAT amount tolerance

myDATA rejects a line when its VAT amount is more than 1.00 away from its net value times the VAT rate
(error 229). Small rounding differences on many lines can go over this limit once they are summed into
one line. With `vatAmountTolerance`, squashing starts a new line with the same categories before the
VAT amount goes over the tolerance.

```php
$invoice->squashInvoiceRows(['vatAmountTolerance' => 1.00]);
```

Without it (`null`), lines with the same categories always become one line.

### Row groups

`SquashInvoiceRows::groups()` returns the original lines of each squashed line, without summing them,
in the same order as the squashed lines. Use it to check a squashed line before sending, or to map it
back to your own lines.

```php
use Firebed\AadeMyData\Actions\SquashInvoiceRows;

$groups = (new SquashInvoiceRows())->groups($invoice->getInvoiceDetails(), ['vatAmountTolerance' => 1.00]);

foreach ($groups as $group) {
    // $group: the original InvoiceDetails of one squashed line
}
```

> [!CAUTION]
> Don't squash every type of invoice. For example, delivery notes don't need it.

## Example

### Invoice XML before squashing

```xml
<invoice>
  ...
  <invoiceDetails>
    <lineNumber>1</lineNumber>
    <netValue>4.03</netValue>
    <vatCategory>1</vatCategory>
    <vatAmount>0.97</vatAmount>
    <incomeClassification>
      <icls:classificationType>E3_561_001</icls:classificationType>
      <icls:classificationCategory>category1_1</icls:classificationCategory>
      <icls:amount>4.03</icls:amount>
    </incomeClassification>
  </invoiceDetails>
  <invoiceDetails>
    <lineNumber>2</lineNumber>
    <netValue>4.03</netValue>
    <vatCategory>1</vatCategory>
    <vatAmount>0.97</vatAmount>
    <incomeClassification>
      <icls:classificationType>E3_561_001</icls:classificationType>
      <icls:classificationCategory>category1_1</icls:classificationCategory>
      <icls:amount>2.02</icls:amount>
    </incomeClassification>
    <incomeClassification>
      <icls:classificationType>E3_561_007</icls:classificationType>
      <icls:classificationCategory>category1_1</icls:classificationCategory>
      <icls:amount>2.01</icls:amount>
    </incomeClassification>
  </invoiceDetails>
  <invoiceDetails>
    <lineNumber>3</lineNumber>
    <netValue>4.03</netValue>
    <vatCategory>1</vatCategory>
    <vatAmount>0.97</vatAmount>
    <incomeClassification>
      <icls:classificationType>E3_561_001</icls:classificationType>
      <icls:classificationCategory>category1_1</icls:classificationCategory>
      <icls:amount>2.02</icls:amount>
    </incomeClassification>
    <incomeClassification>
      <icls:classificationType>E3_561_007</icls:classificationType>
      <icls:classificationCategory>category1_1</icls:classificationCategory>
      <icls:amount>2.01</icls:amount>
    </incomeClassification>
  </invoiceDetails>
  ...
</invoice>
```

### Invoice XML after squashing

With `['clsLineNumber' => true]`. Without it, there is no `icls:id`.

```xml
<invoice>
  ...
  <invoiceDetails>
    <lineNumber>1</lineNumber>
    <netValue>12.09</netValue>
    <vatCategory>1</vatCategory>
    <vatAmount>2.91</vatAmount>
    <incomeClassification>
      <icls:classificationType>E3_561_001</icls:classificationType>
      <icls:classificationCategory>category1_1</icls:classificationCategory>
      <icls:amount>8.07</icls:amount>
      <icls:id>1</icls:id>
    </incomeClassification>
    <incomeClassification>
      <icls:classificationType>E3_561_007</icls:classificationType>
      <icls:classificationCategory>category1_1</icls:classificationCategory>
      <icls:amount>4.02</icls:amount>
      <icls:id>2</icls:id>
    </incomeClassification>
  </invoiceDetails>
  ...
</invoice>
```
