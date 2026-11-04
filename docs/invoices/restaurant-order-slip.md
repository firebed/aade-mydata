# Restaurant order slip

Type `8.6` records an order at a restaurant table. It is not a tax document. You close it with a retail receipt
or an invoice within 24 hours.

Rules:

- Provider channel only. On the ERP channel myDATA rejects it with error 223 (unsupported invoice type).
- `tableAA` (the table number) in the header.
- Every line needs `itemDescr`, `quantity` (above 0) and `measurementUnit`.
- Income classification `category1_95` with no E3 type.
- No payment methods. The payment goes on the receipt that closes the slip.

The example has one line with VAT 24% and one VAT-exempt line. An exempt line uses VAT category `7` (`VatCategory::EXEMPT`),
`vatAmount` 0 and an exemption category. This one uses `27` (other VAT exemptions).

```php
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Enums\UnitMeasurement;
use Firebed\AadeMyData\Enums\VatCategory;
use Firebed\AadeMyData\Enums\VatExemption;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoiceDetails;
use Firebed\AadeMyData\Models\InvoiceHeader;
use Firebed\AadeMyData\Models\Issuer;

// Issuer (the restaurant)
$issuer = new Issuer();
$issuer->setVatNumber('888888888');
$issuer->setCountry('GR');
$issuer->setBranch(0);

// Header
$header = new InvoiceHeader();
$header->setSeries('ORD');
$header->setAa(101);
$header->setIssueDate(date('Y-m-d'));
$header->setInvoiceType(InvoiceType::TYPE_8_6);
$header->setCurrency('EUR');
$header->setTableAA('12');

// VAT 24%
$row1 = new InvoiceDetails();
$row1->setLineNumber(1);
$row1->setItemDescr('Greek salad');
$row1->setQuantity(2);
$row1->setMeasurementUnit(UnitMeasurement::PIECES);
$row1->setNetValue(16);
$row1->setVatCategory(VatCategory::RATE_24);
$row1->setVatAmount(3.84);
$row1->addIncomeClassification(null, IncomeClassificationCategory::CATEGORY_1_95, 16);

// VAT exempt
$row2 = new InvoiceDetails();
$row2->setLineNumber(2);
$row2->setItemDescr('Exempt item');
$row2->setQuantity(1);
$row2->setMeasurementUnit(UnitMeasurement::PIECES);
$row2->setNetValue(5);
$row2->setVatCategory(VatCategory::EXEMPT);
$row2->setVatAmount(0);
$row2->setVatExemptionCategory(VatExemption::OTHER_EXEMPTIONS);
$row2->addIncomeClassification(null, IncomeClassificationCategory::CATEGORY_1_95, 5);

$invoice = new Invoice();
$invoice->setIssuer($issuer);
$invoice->setInvoiceHeader($header);
$invoice->addInvoiceDetails($row1);
$invoice->addInvoiceDetails($row2);

$invoice->summarizeInvoice();
```

Totals: net 21.00, VAT 3.84, gross 24.84.

## Close the slip

Issue the receipt (or invoice) and list the MARKs of the slips it closes in `multipleConnectedMarks`.
One receipt can close many slips. The receipt lines take a real E3 classification, a payment method,
and no `itemDescr` (myDATA rejects it on `11.1` with error 247).

```php
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Enums\PaymentType;
use Firebed\AadeMyData\Enums\VatCategory;
use Firebed\AadeMyData\Enums\VatExemption;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoiceDetails;
use Firebed\AadeMyData\Models\InvoiceHeader;
use Firebed\AadeMyData\Models\PaymentMethodDetail;

$header = new InvoiceHeader();
$header->setSeries('R');
$header->setAa(201);
$header->setIssueDate(date('Y-m-d'));
$header->setInvoiceType(InvoiceType::TYPE_11_1);
$header->setCurrency('EUR');
$header->setMultipleConnectedMarks([$orderSlipMark]);

$row1 = new InvoiceDetails();
$row1->setLineNumber(1);
$row1->setNetValue(16);
$row1->setVatCategory(VatCategory::RATE_24);
$row1->setVatAmount(3.84);
$row1->addIncomeClassification(IncomeClassificationType::E3_561_003, IncomeClassificationCategory::CATEGORY_1_3, 16);

$row2 = new InvoiceDetails();
$row2->setLineNumber(2);
$row2->setNetValue(5);
$row2->setVatCategory(VatCategory::EXEMPT);
$row2->setVatAmount(0);
$row2->setVatExemptionCategory(VatExemption::OTHER_EXEMPTIONS);
$row2->addIncomeClassification(IncomeClassificationType::E3_561_003, IncomeClassificationCategory::CATEGORY_1_3, 5);

$payment = new PaymentMethodDetail();
$payment->setType(PaymentType::CASH);
$payment->setAmount(24.84);

$receipt = new Invoice();
$receipt->setIssuer($issuer);
$receipt->setInvoiceHeader($header);
$receipt->addInvoiceDetails($row1);
$receipt->addInvoiceDetails($row2);
$receipt->addPaymentMethod($payment);

$receipt->summarizeInvoice();
```

## Cancel slips

To cancel slips that will not be closed, send another `8.6` with `totalCancelDeliveryOrders` set to `true`
and their MARKs in `multipleConnectedMarks`. It must have exactly one line, with zero values and VAT category `8`
(error 281 otherwise).

```php
$header = new InvoiceHeader();
$header->setSeries('ORD');
$header->setAa(102);
$header->setIssueDate(date('Y-m-d'));
$header->setInvoiceType(InvoiceType::TYPE_8_6);
$header->setCurrency('EUR');
$header->setTableAA('12');
$header->setTotalCancelDeliveryOrders(true);
$header->setMultipleConnectedMarks([$orderSlipMark1, $orderSlipMark2]);

$row = new InvoiceDetails();
$row->setLineNumber(1);
$row->setItemDescr('Cancel order slips');
$row->setQuantity(1);
$row->setMeasurementUnit(UnitMeasurement::PIECES);
$row->setNetValue(0);
$row->setVatCategory(VatCategory::WITHOUT_VAT);
$row->setVatAmount(0);
$row->addIncomeClassification(null, IncomeClassificationCategory::CATEGORY_1_95, 0);

$cancel = new Invoice();
$cancel->setIssuer($issuer);
$cancel->setInvoiceHeader($header);
$cancel->addInvoiceDetails($row);

$cancel->summarizeInvoice();
```

## Send the invoice

Send through a provider connection (see [Installation](../installation)):

```php
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\SendInvoices;

MyDataRequest::init($userId, $subscriptionKey, 'dev', true); // true: provider channel

$response = (new SendInvoices())->handle($invoice)->first();

if ($response->isSuccessful()) {
    $orderSlipMark = $response->getInvoiceMark();
} else {
    foreach ($response->getErrors() as $error) {
        echo $error->getCode().': '.$error->getMessage().PHP_EOL;
    }
}
```

Keep the MARK: the receipt that closes the slip needs it. See [SendInvoices](../http/send-invoices) for sending many invoices and handling exceptions.
