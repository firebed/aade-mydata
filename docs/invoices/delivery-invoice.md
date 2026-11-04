# Delivery note

A delivery note (type `9.3`). It moves goods and records no income. Here the lines have zero values and VAT category
`WITHOUT_VAT`. Type `9.3` allows only the income category `category3`, with no classification type.
A delivery note needs no payment methods.

```php
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Enums\MovePurpose;
use Firebed\AadeMyData\Enums\UnitMeasurement;
use Firebed\AadeMyData\Enums\VatCategory;
use Firebed\AadeMyData\Models\Address;
use Firebed\AadeMyData\Models\Counterpart;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoiceDetails;
use Firebed\AadeMyData\Models\InvoiceHeader;
use Firebed\AadeMyData\Models\Issuer;
use Firebed\AadeMyData\Models\OtherDeliveryNoteHeader;

// Issuer (you)
$issuer = new Issuer();
$issuer->setVatNumber('888888888');
$issuer->setCountry('GR');
$issuer->setBranch(0);

// Recipient
$address = new Address();
$address->setStreet('Agamemnonos');
$address->setNumber('22');
$address->setPostalCode('22222');
$address->setCity('IRAKLIO');

$counterpart = new Counterpart();
$counterpart->setVatNumber('999999999');
$counterpart->setCountry('GR');
$counterpart->setBranch(0);
$counterpart->setAddress($address);

// Transport details
$loadingAddress = new Address();
$loadingAddress->setStreet('Andronikou');
$loadingAddress->setNumber('22');
$loadingAddress->setPostalCode('11111');
$loadingAddress->setCity('ATHINA');

$deliveryAddress = new Address();
$deliveryAddress->setStreet('Agamemnonos');
$deliveryAddress->setNumber('22');
$deliveryAddress->setPostalCode('22222');
$deliveryAddress->setCity('IRAKLIO');

$deliveryNoteHeader = new OtherDeliveryNoteHeader();
$deliveryNoteHeader->setLoadingAddress($loadingAddress);
$deliveryNoteHeader->setDeliveryAddress($deliveryAddress);

// Header
$header = new InvoiceHeader();
$header->setSeries('D');
$header->setAa(101);
$header->setIssueDate(date('Y-m-d'));
$header->setInvoiceType(InvoiceType::TYPE_9_3);
$header->setMovePurpose(MovePurpose::SALE);
$header->setOtherDeliveryNoteHeader($deliveryNoteHeader);

// Lines
$row1 = new InvoiceDetails();
$row1->setLineNumber(1);
$row1->setItemCode('KDF11');
$row1->setItemDescr('Spigen Liquid Crystal Back Cover');
$row1->setQuantity(1);
$row1->setMeasurementUnit(UnitMeasurement::PIECES);
$row1->setNetValue(0);
$row1->setVatCategory(VatCategory::WITHOUT_VAT);
$row1->setVatAmount(0);
$row1->addIncomeClassification(null, IncomeClassificationCategory::CATEGORY_3, 0);

$row2 = new InvoiceDetails();
$row2->setLineNumber(2);
$row2->setItemCode('KDF22');
$row2->setItemDescr('Spigen Liquid Air Matte Black');
$row2->setQuantity(1);
$row2->setMeasurementUnit(UnitMeasurement::PIECES);
$row2->setNetValue(0);
$row2->setVatCategory(VatCategory::WITHOUT_VAT);
$row2->setVatAmount(0);
$row2->addIncomeClassification(null, IncomeClassificationCategory::CATEGORY_3, 0);

$invoice = new Invoice();
$invoice->setIssuer($issuer);
$invoice->setCounterpart($counterpart);
$invoice->setInvoiceHeader($header);
$invoice->addInvoiceDetails($row1);
$invoice->addInvoiceDetails($row2);

$invoice->summarizeInvoice();
```

> [!NOTE]
> Do not set a name on a Greek issuer or recipient, or an address on a Greek issuer. myDATA rejects them (errors 219, 220).

Optional transport fields on `InvoiceHeader`: `setDispatchDate()`, `setDispatchTime()`, `setVehicleNumber()`.
To follow the goods after sending, see [Digital Goods Movement](../http/digital-goods-movement).
To cancel a delivery note, see [CancelDeliveryNote](../http/cancel-delivery-note).

## Send the invoice

```php
use Firebed\AadeMyData\Http\SendInvoices;

$response = (new SendInvoices())->handle($invoice)->first();

if ($response->isSuccessful()) {
    $mark = $response->getInvoiceMark();
    $uid = $response->getInvoiceUid();
    $qrUrl = $response->getQrUrl();
} else {
    foreach ($response->getErrors() as $error) {
        echo $error->getCode().': '.$error->getMessage().PHP_EOL;
    }
}
```

Save the MARK, uid and QR URL with your invoice. See [SendInvoices](../http/send-invoices) for sending many invoices and handling exceptions.
