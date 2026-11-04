# Service invoice

A service invoice (type `2.1`) to a Greek business. One line at 24% VAT and a 20% withholding tax.

```php
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Enums\PaymentType;
use Firebed\AadeMyData\Enums\TaxType;
use Firebed\AadeMyData\Enums\VatCategory;
use Firebed\AadeMyData\Enums\WithheldPercentCategory;
use Firebed\AadeMyData\Models\Address;
use Firebed\AadeMyData\Models\Counterpart;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoiceDetails;
use Firebed\AadeMyData\Models\InvoiceHeader;
use Firebed\AadeMyData\Models\Issuer;
use Firebed\AadeMyData\Models\PaymentMethodDetail;
use Firebed\AadeMyData\Models\TaxTotals;

// Issuer (you)
$issuer = new Issuer();
$issuer->setVatNumber('888888888');
$issuer->setCountry('GR');
$issuer->setBranch(0);

// Recipient
$address = new Address();
$address->setPostalCode('22222');
$address->setCity('IRAKLIO');

$counterpart = new Counterpart();
$counterpart->setVatNumber('999999999');
$counterpart->setCountry('GR');
$counterpart->setBranch(0);
$counterpart->setAddress($address);

// Header
$header = new InvoiceHeader();
$header->setSeries('A');
$header->setAa(101);
$header->setIssueDate(date('Y-m-d'));
$header->setInvoiceType(InvoiceType::TYPE_2_1);
$header->setCurrency('EUR');

// Line
$row = new InvoiceDetails();
$row->setLineNumber(1);
$row->setNetValue(1000);
$row->setVatCategory(VatCategory::RATE_24);
$row->setVatAmount(240);
$row->addIncomeClassification(
    IncomeClassificationType::E3_561_001,
    IncomeClassificationCategory::CATEGORY_1_3,
    1000
);

// Withholding tax: 20% of 1000
$tax = new TaxTotals();
$tax->setTaxType(TaxType::WITHHELD_TAX);
$tax->setTaxCategory(WithheldPercentCategory::ROYALTIES_20);
$tax->setUnderlyingValue(1000);
$tax->setTaxAmount(200);

// Payment: 1000 + 240 - 200
$payment = new PaymentMethodDetail();
$payment->setType(PaymentType::CASH);
$payment->setAmount(1040);

$invoice = new Invoice();
$invoice->setIssuer($issuer);
$invoice->setCounterpart($counterpart);
$invoice->setInvoiceHeader($header);
$invoice->addInvoiceDetails($row);
$invoice->addTaxesTotals($tax);
$invoice->addPaymentMethod($payment);

$invoice->summarizeInvoice();
```

Totals: net 1,000.00, VAT 240.00, withheld 200.00, gross 1,040.00.

> [!NOTE]
> Type `2.1` accepts only service income categories (`category1_3`, `category1_5`, `category1_8`, `category1_9`, `category1_95`).
> See [Classifications](../classifications) to list the valid combinations.

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
