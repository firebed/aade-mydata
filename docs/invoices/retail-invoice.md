# Retail receipt

A retail sales receipt (type `11.1`). Retail receipts have no counterpart, so do not call `setCounterpart()`.

```php
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Enums\PaymentType;
use Firebed\AadeMyData\Enums\VatCategory;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoiceDetails;
use Firebed\AadeMyData\Models\InvoiceHeader;
use Firebed\AadeMyData\Models\Issuer;
use Firebed\AadeMyData\Models\PaymentMethodDetail;

// Issuer (you)
$issuer = new Issuer();
$issuer->setVatNumber('888888888');
$issuer->setCountry('GR');
$issuer->setBranch(0);

// Header
$header = new InvoiceHeader();
$header->setSeries('R');
$header->setAa(101);
$header->setIssueDate(date('Y-m-d'));
$header->setInvoiceType(InvoiceType::TYPE_11_1);
$header->setCurrency('EUR');

// Line
$row = new InvoiceDetails();
$row->setLineNumber(1);
$row->setNetValue(100);
$row->setVatCategory(VatCategory::RATE_24);
$row->setVatAmount(24);
$row->addIncomeClassification(
    IncomeClassificationType::E3_561_003,
    IncomeClassificationCategory::CATEGORY_1_1,
    100
);

// Payment: 100 + 24
$payment = new PaymentMethodDetail();
$payment->setType(PaymentType::CASH);
$payment->setAmount(124);

$invoice = new Invoice();
$invoice->setIssuer($issuer);
$invoice->setInvoiceHeader($header);
$invoice->addInvoiceDetails($row);
$invoice->addPaymentMethod($payment);

$invoice->summarizeInvoice();
```

Totals: net 100.00, VAT 24.00, gross 124.00.

> [!NOTE]
> `E3_561_003` is the classification type for retail sales to private customers.

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
