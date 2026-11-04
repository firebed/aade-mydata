# Invoice and delivery note

A sales invoice (type `1.1`) that is also a delivery note. Set `isDeliveryNote` to `true` and add the transport details:
the move purpose and the loading and delivery addresses.

```php
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Enums\MovePurpose;
use Firebed\AadeMyData\Enums\PaymentType;
use Firebed\AadeMyData\Enums\UnitMeasurement;
use Firebed\AadeMyData\Enums\VatCategory;
use Firebed\AadeMyData\Models\Address;
use Firebed\AadeMyData\Models\Counterpart;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoiceDetails;
use Firebed\AadeMyData\Models\InvoiceHeader;
use Firebed\AadeMyData\Models\Issuer;
use Firebed\AadeMyData\Models\OtherDeliveryNoteHeader;
use Firebed\AadeMyData\Models\PaymentMethodDetail;

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
$header->setSeries('A');
$header->setAa(101);
$header->setIssueDate(date('Y-m-d'));
$header->setInvoiceType(InvoiceType::TYPE_1_1);
$header->setCurrency('EUR');
$header->setIsDeliveryNote(true);
$header->setMovePurpose(MovePurpose::SALE);
$header->setOtherDeliveryNoteHeader($deliveryNoteHeader);

// Lines
$row1 = new InvoiceDetails();
$row1->setLineNumber(1);
$row1->setItemCode('KDF11');
$row1->setItemDescr('Spigen Liquid Crystal Back Cover');
$row1->setQuantity(1);
$row1->setMeasurementUnit(UnitMeasurement::PIECES);
$row1->setNetValue(1000);
$row1->setVatCategory(VatCategory::RATE_24);
$row1->setVatAmount(240);
$row1->addIncomeClassification(
    IncomeClassificationType::E3_561_001,
    IncomeClassificationCategory::CATEGORY_1_2,
    1000
);

$row2 = new InvoiceDetails();
$row2->setLineNumber(2);
$row2->setItemCode('KDF22');
$row2->setItemDescr('Spigen Liquid Air Matte Black');
$row2->setQuantity(1);
$row2->setMeasurementUnit(UnitMeasurement::PIECES);
$row2->setNetValue(500);
$row2->setVatCategory(VatCategory::RATE_24);
$row2->setVatAmount(120);
$row2->addIncomeClassification(
    IncomeClassificationType::E3_561_001,
    IncomeClassificationCategory::CATEGORY_1_2,
    500
);

// Payment: 1500 + 360
$payment = new PaymentMethodDetail();
$payment->setType(PaymentType::CASH);
$payment->setAmount(1860);

$invoice = new Invoice();
$invoice->setIssuer($issuer);
$invoice->setCounterpart($counterpart);
$invoice->setInvoiceHeader($header);
$invoice->addInvoiceDetails($row1);
$invoice->addInvoiceDetails($row2);
$invoice->addPaymentMethod($payment);

$invoice->summarizeInvoice();
```

Totals: net 1,500.00, VAT 360.00, gross 1,860.00.

> [!NOTE]
> Do not set a name on a Greek issuer or recipient, or an address on a Greek issuer. myDATA rejects them (errors 219, 220).

Optional transport fields on `InvoiceHeader`: `setDispatchDate()`, `setDispatchTime()`, `setVehicleNumber()`.
To follow the goods after sending, see [Digital Goods Movement](../http/digital-goods-movement).

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
