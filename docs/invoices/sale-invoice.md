# Παράδειγμα: Τιμολόγιο Πώλησης

## Αρχικοποίηση του εκδότη παραστατικού

```php
use Firebed\AadeMyData\Models\Issuer;

$issuer = new Issuer();
$issuer->setVatNumber('888888888');
$issuer->setCountry('GR');
$issuer->setBranch(1);
```

## Αρχικοποίηση του λήπτη παραστατικού

```php
use Firebed\AadeMyData\Models\Address;
use Firebed\AadeMyData\Models\Counterpart;

$address = new Address();
$address->setPostalCode('22222');
$address->setCity('IRAKLIO');

$counterpart = new Counterpart();
$counterpart->setVatNumber('999999999');
$counterpart->setCountry('GR');
$counterpart->setBranch(0);
$counterpart->setAddress($address);
```

## Αρχικοποίηση της επικεφαλίδας παραστατικού

```php
use Firebed\AadeMyData\Models\InvoiceHeader;
use Firebed\AadeMyData\Enums\InvoiceType;

$header = new InvoiceHeader();
$header->setSeries('A');
$header->setAa(101);
$header->setIssueDate('2020-04-08');
$header->setInvoiceType(InvoiceType::TYPE_1_1);
$header->setCurrency('EUR');
```

## Αρχικοποίηση των τρόπων πληρωμής

```php
use Firebed\AadeMyData\Models\PaymentMethodDetail;
use Firebed\AadeMyData\Enums\PaymentMethod;

$payment = new PaymentMethodDetail();
$payment->setType(PaymentMethod::METHOD_3);
$payment->setAmount(1760);
$payment->setPaymentMethodInfo('Payment Method Info...');
```

## Αρχικοποίηση των προϊόντων

```php
use Firebed\AadeMyData\Models\InvoiceDetails;
use Firebed\AadeMyData\Enums\VatCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;

$row1 = new InvoiceDetails();
$row1->setLineNumber(1);
$row1->setNetValue(1000);
$row1->setVatCategory(VatCategory::VAT_1);
$row1->setVatAmount(240);
$row1->setDiscountOption(true);
$row1->addIncomeClassification(
    IncomeClassificationType::E3_561_001, 
    IncomeClassificationCategory::CATEGORY_1_2, 
    1000
);

$row2 = new InvoiceDetails();
$row2->setLineNumber(2);
$row2->setNetValue(500);
$row2->setVatCategory(VatCategory::VAT_1);
$row2->setVatAmount(120);
$row2->setDiscountOption(true);
$row2->addIncomeClassification(
    IncomeClassificationType::E3_561_001, 
    IncomeClassificationCategory::CATEGORY_1_3, 
    500
);
```

## Αρχικοποίηση φόρων παραστατικού

```php
use Firebed\AadeMyData\Models\TaxTotals;
use Firebed\AadeMyData\Enums\TaxType;
use Firebed\AadeMyData\Enums\WithheldPercentCategory;

$tax = new TaxTotals();
$tax->setTaxType(TaxType::TYPE_1);
$tax->setUnderlyingValue(500);
$tax->setTaxCategory(WithheldPercentCategory::TAX_2);
$tax->setTaxAmount(100);
```

## Ολοκλήρωση του παραστατικού

```php
use Firebed\AadeMyData\Models\Invoice;

$invoice = new Invoice();
$invoice->setIssuer($issuer);
$invoice->setCounterpart($counterpart);
$invoice->setInvoiceHeader($header);
$invoice->addPaymentMethod($payment);
$invoice->addInvoiceDetails($row1);
$invoice->addInvoiceDetails($row2);
$invoice->addTaxesTotals($tax);

// Αρχικοποιεί το InvoiceSummary
$invoice->summarizeInvoice();
```