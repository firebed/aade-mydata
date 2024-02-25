# Παράδειγμα: Τιμολόγιο Πώλησης (Ενδοκοινοτικές)

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
$address->setPostalCode('RO-22222');
$address->setCity('ROME');

$counterpart = new Counterpart();
$counterpart->setVatNumber('ΙΤ55555555');
$counterpart->setCountry('IT');
$counterpart->setBranch(0);
$counterpart->setName('ITA-COMPANY S.A.');
$counterpart->setAddress($address);
```

## Αρχικοποίηση της επικεφαλίδας παραστατικού

```php
use Firebed\AadeMyData\Models\InvoiceHeader;
use Firebed\AadeMyData\Enums\InvoiceType;

$header = new InvoiceHeader();
$header->setSeries('AK42');
$header->setAa(101);
$header->setIssueDate('2020-04-08');

// Τιμολόγιο Πώλησης / Ενδοκοινοτικές Παραδόσεις
$header->setInvoiceType(InvoiceType::TYPE_1_2);

$header->setCurrency('EUR');
```

## Αρχικοποίηση των τρόπων πληρωμής

```php
use Firebed\AadeMyData\Models\PaymentMethodDetail;
use Firebed\AadeMyData\Enums\PaymentMethod;

$payment = new PaymentMethodDetail();
$payment->setType(PaymentMethod::METHOD_1);
$payment->setAmount(10000.00);
$payment->setPaymentMethodInfo('GR1234567890123456789012345');
```

## Αρχικοποίηση των προϊόντων

```php
use Firebed\AadeMyData\Models\InvoiceDetails;
use Firebed\AadeMyData\Enums\VatCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;

$row = new InvoiceDetails();
$row->setLineNumber(1);
$row->setNetValue(10000.00);
$row->setVatCategory(VatCategory::VAT_1);
$row->setNetValue(2400.00);
$row->addIncomeClassification(
    IncomeClassificationType::E3_561_005, // Πωλήσεις αγαθών και υπηρεσιών Εξωτερικού 
    IncomeClassificationCategory::CATEGORY_1_1, // Έσοδα από Πώληση Εμπορευμάτων 
    10000.00
);
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

// Αρχικοποιεί τη σύνοψη του παραστατικού
$invoice->summarizeInvoice();
```