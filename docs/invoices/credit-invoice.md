# Credit invoice (correlated)

A credit invoice (type `5.1`) for part or all of an invoice you already sent. Add the MARK of that invoice
with `addCorrelatedInvoice()`. Amounts stay positive (myDATA does not accept negative amounts). The type makes it a credit.

```php
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Enums\PaymentType;
use Firebed\AadeMyData\Enums\VatCategory;
use Firebed\AadeMyData\Models\Address;
use Firebed\AadeMyData\Models\Counterpart;
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

// Recipient: the same as on the original invoice
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
$header->setSeries('C');
$header->setAa(101);
$header->setIssueDate(date('Y-m-d'));
$header->setInvoiceType(InvoiceType::TYPE_5_1);
$header->setCurrency('EUR');
$header->addCorrelatedInvoice(400000017716190); // MARK of the original invoice

// Lines: classify them as on the original invoice
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

- The correlated MARK must exist in myDATA. Send the original invoice first.
- Use the classifications that the original invoice type allows. Here the original is a [sales invoice](./sale-invoice) (`1.1`).
- All credits against one invoice together can't exceed its net value (error 319).
- No MARK to refer to? Use a [credit invoice (not correlated)](./credit-invoice-uncorrelated).

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
