# Export invoice

A sales invoice for goods exported to a country outside the EU (type `1.3`). The line is VAT exempt:
VAT category `EXEMPT` with the exemption `ARTICLE_29` (exports).
The recipient has a name and address. myDATA accepts a name only for parties outside Greece.

```php
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Enums\PaymentType;
use Firebed\AadeMyData\Enums\VatCategory;
use Firebed\AadeMyData\Enums\VatExemption;
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

// Recipient in the United States
$address = new Address();
$address->setStreet('Main Street');
$address->setNumber('100');
$address->setPostalCode('10001');
$address->setCity('NEW YORK');

$counterpart = new Counterpart();
$counterpart->setVatNumber('123456789');
$counterpart->setCountry('US');
$counterpart->setBranch(0);
$counterpart->setName('US-COMPANY INC.');
$counterpart->setAddress($address);

// Header
$header = new InvoiceHeader();
$header->setSeries('X');
$header->setAa(101);
$header->setIssueDate(date('Y-m-d'));
$header->setInvoiceType(InvoiceType::TYPE_1_3);
$header->setCurrency('EUR');

// Line: no VAT, with the exemption reason
$row = new InvoiceDetails();
$row->setLineNumber(1);
$row->setNetValue(10000);
$row->setVatCategory(VatCategory::EXEMPT);
$row->setVatAmount(0);
$row->setVatExemptionCategory(VatExemption::ARTICLE_29);
$row->addIncomeClassification(
    IncomeClassificationType::E3_561_006,       // sales of goods and services abroad, third countries
    IncomeClassificationCategory::CATEGORY_1_1, // income from sale of goods
    10000
);

// Payment by bank transfer
$payment = new PaymentMethodDetail();
$payment->setType(PaymentType::DOMESTIC_PAYMENTS_ACCOUNT);
$payment->setAmount(10000);
$payment->setPaymentMethodInfo('GR1234567890123456789012345');

$invoice = new Invoice();
$invoice->setIssuer($issuer);
$invoice->setCounterpart($counterpart);
$invoice->setInvoiceHeader($header);
$invoice->addInvoiceDetails($row);
$invoice->addPaymentMethod($payment);

$invoice->summarizeInvoice();
```

Totals: net 10,000.00, VAT 0.00, gross 10,000.00.

> [!NOTE]
> With VAT category `EXEMPT` the exemption category is required (error 217).
> See [VAT exemption categories](../appendix/vat-exemption-categories) for the full list.
> For a sale to a business in another EU country, see the [intra-community invoice](./foreign-invoice).

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
