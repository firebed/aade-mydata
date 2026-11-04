# PaymentMethod

`PaymentMethod` adds payment methods to an invoice that is already in myDATA. You send it with
`SendPaymentsMethod`. `RequestDocs` also returns it, in `RequestedDoc::getPaymentMethods()`.

```php
use Firebed\AadeMyData\Enums\PaymentType;
use Firebed\AadeMyData\Http\SendPaymentsMethod;
use Firebed\AadeMyData\Models\PaymentMethod;
use Firebed\AadeMyData\Models\PaymentMethodDetail;

$detail = new PaymentMethodDetail();
$detail->setType(PaymentType::POS);
$detail->setAmount(1000);
$detail->setTid('12345678');
$detail->setECRToken('ABC1234567', '123456');

$paymentMethod = new PaymentMethod();
$paymentMethod->setInvoiceMark(400001234567890);
$paymentMethod->addPaymentMethodDetails($detail);

$responses = (new SendPaymentsMethod())->handle($paymentMethod);
```

| Field                | Type                                                  | Required | Description |
|----------------------|-------------------------------------------------------|----------|-------------|
| invoiceMark          | `int`                                                 | Yes      | MARK of the invoice |
| paymentMethodMark    | `int`                                                 | No       | MARK of the payment methods. Set by myDATA, getter only |
| entityVatNumber      | `string`                                              | No       | VAT number of the entity. Only when a third party (e.g. a provider) sends it |
| paymentMethodDetails | [`PaymentMethodDetail[]`](payment-method-detail-type) | Yes      | Payments. Add one with `addPaymentMethodDetails()` |

> [!NOTE]
> The amounts of one `PaymentMethod` must add up to the total gross value of the invoice. At least one payment
> per invoice must be `PaymentType::POS`.
