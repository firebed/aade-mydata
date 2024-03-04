# Παράδειγμα: Δελτίο Αποστολής

## Αρχικοποίηση του εκδότη παραστατικού

```php
use Firebed\AadeMyData\Models\Address;
use Firebed\AadeMyData\Models\Issuer;

$address = new Address();
$address->setStreet('Ανδρονίκου');
$address->setNumber('22');
$address->setPostalCode('22222');
$address->setCity('ΑΘΗΝΑ');

$issuer = new Issuer();
$issuer->setVatNumber('888888888');
$issuer->setCountry('GR');
$issuer->setBranch(1);
$issuer->setName('Επωνυμία εκδότη');
$issuer->setAddress($address);
```

## Αρχικοποίηση του λήπτη παραστατικού

```php
use Firebed\AadeMyData\Models\Address;
use Firebed\AadeMyData\Models\Counterpart;

$address = new Address();
$address->setStreet('Αγαμέμνονος');
$address->setNumber('22');
$address->setPostalCode('22222');
$address->setCity('IRAKLIO');

$counterpart = new Counterpart();
$counterpart->setVatNumber('999999999');
$counterpart->setCountry('GR');
$counterpart->setBranch(0);
$counterpart->setName('Επωνυμία λήπτη');
$counterpart->setAddress($address);
```

## Αρχικοποίηση της επικεφαλίδας παραστατικού

```php
use Firebed\AadeMyData\Models\Address;
use Firebed\AadeMyData\Enums\OtherDeliveryNoteHeader;
use Firebed\AadeMyData\Models\InvoiceHeader;
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Enums\MovePurpose;

$loadingAddress = new Address();
$loadingAddress->setStreet('Ανδρονίκου');
$loadingAddress->setNumber('22');
$loadingAddress->setPostalCode('22222');
$loadingAddress->setCity('IRAKLIO');

$deliveryAddress = new Address();
$deliveryAddress->setStreet('Αγαμέμνονος');
$deliveryAddress->setNumber('22');
$deliveryAddress->setPostalCode('22222');
$deliveryAddress->setCity('IRAKLIO');

$otherDeliveryNoteHeader = new OtherDeliveryNoteHeader();
$otherDeliveryNoteHeader->setLoadingAddress($loadingAddress);
$otherDeliveryNoteHeader->setDeliveryAddress($deliveryAddress);

$header = new InvoiceHeader();
$header->setSeries('A');
$header->setAa(101);
$header->setIssueDate('2020-04-08');
$header->setInvoiceType(InvoiceType::TYPE_9_3);
$header->setMovePurpose(MovePurpose::TYPE_1);
$header->setOtherDeliveryNoteHeader($otherDeliveryNoteHeader);
```

## Αρχικοποίηση των τρόπων πληρωμής

> [!CAUTION]
> Παραστατικά Δελτίο Αποστολής δεν απαιτούν την αναγραφή των τρόπων πληρωμής.

## Αρχικοποίηση των προϊόντων

```php
use Firebed\AadeMyData\Models\InvoiceDetails;
use Firebed\AadeMyData\Enums\VatCategory;
use Firebed\AadeMyData\Enums\UnitMeasurement;
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;

$row1 = new InvoiceDetails();
$row1->setLineNumber(1);
$row1->setItemCode('#KDF11');
$row1->setItemDescr('Spigen Liquid Crystal Back Cover');
$row1->setQuantity('1');
$row1->setMeasurementUnit(UnitMeasurement::UNIT_1);
$row1->setNetValue(0);
$row1->setVatCategory(VatCategory::VAT_8);
$row1->setVatAmount(0);
$row1->addIncomeClassification(
    NULL, 
    IncomeClassificationCategory::CATEGORY_3, 
    0
);

$row2 = new InvoiceDetails();
$row2->setLineNumber(2);
$row2->setItemCode('#KDF22');
$row2->setItemDescr('Spigen Liquid Air Matte Black');
$row2->setQuantity('1');
$row2->setMeasurementUnit(UnitMeasurement::UNIT_1);
$row2->setNetValue(0);
$row2->setVatCategory(VatCategory::VAT_8);
$row2->setVatAmount(0);
$row2->addIncomeClassification(
    NULL, 
    IncomeClassificationCategory::CATEGORY_3, 
    0
);
```

## Ολοκλήρωση του παραστατικού

```
use Firebed\AadeMyData\Models\Invoice;

$invoice = new Invoice();
$invoice->setIssuer($issuer);
$invoice->setCounterpart($counterpart);
$invoice->setInvoiceHeader($header);
$invoice->addInvoiceDetails($row1);
$invoice->addInvoiceDetails($row2);

// Αρχικοποιεί το InvoiceSummary
$invoice->summarizeInvoice();
```
