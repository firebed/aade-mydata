# Retail credit note

A retail credit note (type `11.4`), for example a refund on a [retail receipt](./retail-invoice).
Like retail receipts, it has no counterpart. Amounts stay positive.

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
$header->setSeries('RC');
$header->setAa(101);
$header->setIssueDate(date('Y-m-d'));
$header->setInvoiceType(InvoiceType::TYPE_11_4);
$header->setCurrency('EUR');
// Optional: the MARK of the receipt you refund
// $header->addCorrelatedInvoice(400000017716190);

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

// Refund: 100 + 24
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
> If you can't link the credit note to the original receipt, myDATA has a special invoice category for that:
> `$header->setSpecialInvoiceCategory(SpecialInvoiceCategory::CORRELATION_DIFFICULTY)`. It is valid only for types `11.4` and `14.30`.

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
