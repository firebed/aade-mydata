# PaymentMethodDetail

`PaymentMethodDetail` is one payment of the invoice. Add it with `Invoice::addPaymentMethod()`, or to a
[PaymentMethod](payment-method-type) when you send payments on their own.

```php
use Firebed\AadeMyData\Enums\PaymentType;
use Firebed\AadeMyData\Models\PaymentMethodDetail;

$payment = new PaymentMethodDetail();
$payment->setType(PaymentType::WEB_BANKING);
$payment->setAmount(50.5);
$payment->setPaymentMethodInfo('GR1601101250000000012300695');

$invoice->addPaymentMethod($payment);
```

| Field              | Type                                                | Required | Description                                   |
|--------------------|-----------------------------------------------------|----------|-----------------------------------------------|
| type               | [`PaymentType`](../appendix/payment-methods)        | Yes      | Payment type                                  |
| amount             | `float`                                             | Yes      | Amount. Can be part of the invoice total      |
| paymentMethodInfo  | `string`                                            | No       | Extra info, e.g. a bank account number        |
| tipAmount          | `float`                                             | No       | Tip amount                                    |
| transactionId      | `string`                                            | No       | Unique payment id                             |
| tid                | `string`                                            | No       | POS terminal id (max 200)                     |
| ProvidersSignature | [ProvidersSignature](providers-signature-type)      | No       | Payment signature, provider channel. Methods: `setProvidersSignature()`, `getProvidersSignature()` |
| ECRToken           | [ECRToken](ecr-token-type)                          | No       | Payment signature, ERP channel. Methods: `setECRToken()`, `getECRToken()` |

## POS payments (`PaymentType::POS`)

- Send `transactionId`.
- From the provider channel, send `ProvidersSignature`. From an ERP, send `ECRToken`.
- When you send either signature, `tid` is required.

```php
$payment = new PaymentMethodDetail();
$payment->setType(PaymentType::POS);
$payment->setAmount(50);
$payment->setTid('12345678');
$payment->setECRToken('ABC1234567', '123456'); // signing author, session number
```

`setProvidersSignature($signingAuthor, $signature)` takes two strings in the same way.
Both setters also take a model object.
