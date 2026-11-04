# AADE myDATA REST API

`firebed/aade-mydata` is a PHP client for the myDATA REST API of AADE (the Greek tax authority).
It sends, cancels and requests invoices, sends income and expense classifications and payments,
and reads VAT and E3 reports. It writes and reads the XML for you, in the field order the XSD expects.

It is free and open source. To set it up, see [Installation](./installation).

```php
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Enums\PaymentType;
use Firebed\AadeMyData\Enums\VatCategory;
use Firebed\AadeMyData\Http\MyDataRequest;
use Firebed\AadeMyData\Http\SendInvoices;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\InvoiceDetails;
use Firebed\AadeMyData\Models\InvoiceHeader;
use Firebed\AadeMyData\Models\Issuer;
use Firebed\AadeMyData\Models\PaymentMethodDetail;

MyDataRequest::init('your-user-id', 'your-subscription-key', 'dev');

$invoice = (new Invoice())
    ->setIssuer((new Issuer())->setVatNumber('888888888')->setCountry('GR')->setBranch(0))
    ->setInvoiceHeader((new InvoiceHeader())
        ->setSeries('A')
        ->setAa(101)
        ->setIssueDate('2026-11-04')
        ->setInvoiceType(InvoiceType::TYPE_11_1)
        ->setCurrency('EUR'))
    ->addPaymentMethod((new PaymentMethodDetail())->setType(PaymentType::CASH)->setAmount(12.40))
    ->addInvoiceDetails((new InvoiceDetails())
        ->setLineNumber(1)
        ->setNetValue(10)
        ->setVatCategory(VatCategory::RATE_24)
        ->setVatAmount(2.40)
        ->addIncomeClassification(IncomeClassificationType::E3_561_003, IncomeClassificationCategory::CATEGORY_1_1, 10))
    ->summarizeInvoice(); // fills in the invoice summary

$response = (new SendInvoices())->handle($invoice)->first();

if ($response->isSuccessful()) {
    echo $response->getInvoiceMark();
} else {
    foreach ($response->getErrors() as $error) {
        echo $error->getCode().': '.$error->getMessage();
    }
}
```

More invoice types: [Examples](./invoices/examples). Errors: [Exceptions](./exceptions).

> [!NOTE]
> Not a developer? You can issue invoices to myDATA at [invoicemaker.gr](https://www.invoicemaker.gr).

## Custom gateway

Every request reaches the remote system through a `Firebed\AadeMyData\Http\Gateway`.
The default `GuzzleGateway` calls the AADE myDATA REST API. Register your own gateway
to send requests somewhere else (for example to an e-invoicing provider) without
changing the code that builds them:

```php
use Firebed\AadeMyData\Http\MyDataRequest;

MyDataRequest::setGateway(new MyGateway()); // implements Gateway
MyDataRequest::setGateway(null);            // back to the default
```

`setGateway()` is global to the process and stays until you replace it. In a long-running
worker (Octane, a queue daemon) a gateway set for one flow carries every later request of that
process. If the gateway depends on the tenant or the flow, set it on the request instead:

```php
$response = (new SendInvoices())->usingGateway(new MyGateway())->handle($invoice);
```

`Connection::withGateway($gateway)` does the same for every request of a connection.

A gateway gets the request object and the XML body. For `SendInvoices` and `SendPaymentsMethod`
it can also read the models, with `SendInvoices::getInvoicesDoc()` and
`SendPaymentsMethod::getPaymentMethodsDoc()`. So a provider gateway can read attributes that
never reach the myDATA XML. myDATA only takes the issue date and the line's net value, but a
provider needs more. These stay on the models and are left out of the XML:

| Method | Value |
|---|---|
| `InvoiceHeader::setIssueTime('hh:mm:ss')` | Issue time |
| `InvoiceDetails::setUnitPrice(3.333333)` | Price per unit |
| `InvoiceDetails::setDiscount(DiscountType::PERCENTAGE, 10.0)` (or `DiscountType::AMOUNT`) | Line discount, read back with `getDiscountType()` and `getDiscountValue()` |
| `setExtraFields(['key' => 'value'])`, `addExtraField('key', 'value')` on `Invoice`, `InvoiceDetails` and `PaymentMethodDetail` | Free key/value pairs for the provider |
