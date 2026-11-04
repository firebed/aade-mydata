# Climate resilience fee

Type `8.2` records the climate resilience fee a hotel or short-term rental collects from its guests.

How to send it:

- Net value `0`, VAT category `8` (no VAT). The fee is not income and has no VAT.
- The fee goes in "other taxes", with the category for your accommodation type ([Other taxes categories](../appendix/other-taxes-percent-categories), codes 20 to 30).
- Income classification `category1_95` with no E3 type. It does not update E3.

```php
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Enums\OtherTaxesPercentCategory;
use Firebed\AadeMyData\Enums\PaymentType;
use Firebed\AadeMyData\Enums\VatCategory;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoiceDetails;
use Firebed\AadeMyData\Models\InvoiceHeader;
use Firebed\AadeMyData\Models\Issuer;
use Firebed\AadeMyData\Models\PaymentMethodDetail;

// Issuer (the hotel)
$issuer = new Issuer();
$issuer->setVatNumber('888888888');
$issuer->setCountry('GR');
$issuer->setBranch(0);

// Header
$header = new InvoiceHeader();
$header->setSeries('CRF');
$header->setAa(101);
$header->setIssueDate(date('Y-m-d'));
$header->setInvoiceType(InvoiceType::TYPE_8_2);
$header->setCurrency('EUR');

// One room, 3 nights, 4-star hotel: 3 x 7.00 = 21.00
$row = new InvoiceDetails();
$row->setLineNumber(1);
$row->setNetValue(0);
$row->setVatCategory(VatCategory::WITHOUT_VAT);
$row->setVatAmount(0);
$row->setOtherTaxesPercentCategory(OtherTaxesPercentCategory::HOTELS_4_STARS_7_EUR);
$row->setOtherTaxesAmount(21);
$row->addIncomeClassification(null, IncomeClassificationCategory::CATEGORY_1_95, 0);

$payment = new PaymentMethodDetail();
$payment->setType(PaymentType::CASH);
$payment->setAmount(21);

$invoice = new Invoice();
$invoice->setIssuer($issuer);
$invoice->setInvoiceHeader($header);
$invoice->addInvoiceDetails($row);
$invoice->addPaymentMethod($payment);

$invoice->summarizeInvoice();
```

Totals: net 0.00, VAT 0.00, other taxes 21.00, gross 21.00.

> [!NOTE]
> You can put the fee in the invoice-level taxes instead of the line: a `TaxTotals` with `TaxType::OTHER_TAXES`
> and the same category. Use one or the other, not both. See [TaxTotals](../models/tax-totals-type).

A business that receives a `8.2` classifies it as an expense with `category2_5` and `E3_585_005`, `E3_585_006` or
`E3_585_016`.

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
