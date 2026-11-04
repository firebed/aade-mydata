# Sales invoice

A sales invoice (type `1.1`) to a Greek business. Two lines at 24% VAT and a 20% withholding tax on the second line.

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
$header->setInvoiceType(InvoiceType::TYPE_1_1);
$header->setCurrency('EUR');

// Lines
$row1 = new InvoiceDetails();
$row1->setLineNumber(1);
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
$row2->setNetValue(500);
$row2->setVatCategory(VatCategory::RATE_24);
$row2->setVatAmount(120);
$row2->addIncomeClassification(
    IncomeClassificationType::E3_561_001,
    IncomeClassificationCategory::CATEGORY_1_3,
    500
);

// Withholding tax for the whole invoice: 20% of line 2
$tax = new TaxTotals();
$tax->setTaxType(TaxType::WITHHELD_TAX);
$tax->setTaxCategory(WithheldPercentCategory::ROYALTIES_20);
$tax->setUnderlyingValue(500);
$tax->setTaxAmount(100);

// Payment: net + VAT - withheld = 1500 + 360 - 100
$payment = new PaymentMethodDetail();
$payment->setType(PaymentType::CASH);
$payment->setAmount(1760);

$invoice = new Invoice();
$invoice->setIssuer($issuer);
$invoice->setCounterpart($counterpart);
$invoice->setInvoiceHeader($header);
$invoice->addInvoiceDetails($row1);
$invoice->addInvoiceDetails($row2);
$invoice->addTaxesTotals($tax);
$invoice->addPaymentMethod($payment);

// Calculates the invoice summary (totals) from the lines and taxes
$invoice->summarizeInvoice();
```

Totals: net 1,500.00, VAT 360.00, withheld 100.00, gross 1,760.00.

> [!NOTE]
> Declare taxes other than VAT either per invoice (`addTaxesTotals()`) or per line, not both.
> For the per-line version see [Taxes per line](./taxes-per-row).

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
