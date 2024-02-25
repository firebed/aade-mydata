# Παράδειγμα: Απόδειξη Λιανικής

```php
use Firebed\AadeMyData\Models\Issuer;

$issuer = new Issuer();
$issuer->setVatNumber('888888888');
$issuer->setCountry('GR');
$issuer->setBranch(1);
```

## Αρχικοποίηση του λήπτη παραστατικού

> [!CAUTION]
> Οι λιανικές πωλήσεις είναι μή αντικριζόμενα παραστατικά και δεν απαιτούν την αναγραφή του λήπτη του παραστατικού.

## Αρχικοποίηση της επικεφαλίδας παραστατικού

```php
use Firebed\AadeMyData\Models\InvoiceHeader;
use Firebed\AadeMyData\Enums\InvoiceType;

$header = new InvoiceHeader();
$header->setSeries('A');
$header->setAa(101);
$header->setIssueDate('2020-04-08');
$header->setInvoiceType(InvoiceType::TYPE_11_1);
$header->setCurrency('EUR');
```

## Αρχικοποίηση των τρόπων πληρωμής

```php
use Firebed\AadeMyData\Models\PaymentMethodDetail;
use Firebed\AadeMyData\Enums\PaymentMethod;

$payment = new PaymentMethodDetail();
$payment->setType(PaymentMethod::METHOD_3);
$payment->setAmount(1000.00);
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
$row->addIncomeClassification(
    IncomeClassificationType::E3_561_003, 
    IncomeClassificationCategory::CATEGORY_1_1,
     1000
 );
```

## Ολοκλήρωση του παραστατικού

```php
use Firebed\AadeMyData\Models\Invoice;

$invoice = new Invoice();
$invoice->setIssuer($issuer);
$invoice->setInvoiceHeader($header);
$invoice->addPaymentMethod($payment);
$invoice->addInvoiceDetails($row);

// Αρχικοποιεί το σύνοψη του παραστατικού
$invoice->summarizeInvoice();
```