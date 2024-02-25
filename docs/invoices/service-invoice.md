# Παράδειγμα: Τιμολόγιο Παροχής Υπηρεσιών

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
$header->setInvoiceType(InvoiceType::TYPE_2_1);
$header->setCurrency('EUR');
```

## Αρχικοποίηση των τρόπων πληρωμής

```php
use Firebed\AadeMyData\Models\PaymentMethodDetail;
use Firebed\AadeMyData\Enums\PaymentMethod;

$payment = new PaymentMethodDetail();
$payment->setType(PaymentMethod::METHOD_3);
$payment->setAmount(1040);
$payment->setPaymentMethodInfo('Payment Method Info...');
```

## Αρχικοποίηση των προϊόντων

```php
use Firebed\AadeMyData\Models\InvoiceDetails;
use Firebed\AadeMyData\Enums\VatCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;

$row = new InvoiceDetails();
$row->setLineNumber(1);
$row->setNetValue(1000);
$row->setVatCategory(VatCategory::VAT_1);
$row->setVatAmount(240);
$row->setDiscountOption(true);
$row->addIncomeClassification(
    IncomeClassificationType::E3_561_001, 
    IncomeClassificationCategory::CATEGORY_1_3, 
    1000
);
```

## Αρχικοποίηση φόρων παραστατικού

```php
use Firebed\AadeMyData\Models\TaxTotals;
use Firebed\AadeMyData\Enums\TaxType;
use Firebed\AadeMyData\Enums\WithheldPercentCategory;

$tax = new TaxTotals();
$tax->setTaxType(TaxType::TYPE_1);
$tax->setTaxCategory(WithheldPercentCategory::TAX_2);
$tax->setUnderlyingValue(1000);
$tax->setTaxAmount(200);
```

## Ολοκλήρωση του παραστατικού

```php
use Firebed\AadeMyData\Models\Invoice;

$invoice = new Invoice();
$invoice->setIssuer($issuer);
$invoice->setCounterpart($counterpart);
$invoice->setInvoiceHeader($header);
$invoice->addPaymentMethod($payment);
$invoice->addInvoiceDetails($row);
$invoice->addTaxesTotals($tax);

// Αρχικοποιεί το σύνοψη του παραστατικού
$invoice->summarizeInvoice();
```