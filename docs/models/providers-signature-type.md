# ProvidersSignature

`ProvidersSignature` is the signature of a payment when the invoice goes through the provider channel. It belongs to a
[PaymentMethodDetail](payment-method-detail-type).

```php
use Firebed\AadeMyData\Models\ProvidersSignature;

$signature = new ProvidersSignature('2023_123', 'signature-string');

// IRIS payment: add the payment request id
$signature->setEndToEndReferenceID('E2E-REF-0001');
```

| Field               | Type     | Required | Description                                           |
|---------------------|----------|----------|-------------------------------------------------------|
| SigningAuthor       | `string` | Yes      | Approval decision number of the provider (max 20)     |
| Signature           | `string` | Yes      | Signature                                             |
| EndToEndReferenceID | `string` | No       | Unique id of the payment request, for IRIS payments (`PaymentType::IRIS`) |

Details of `Signature` in decision A.1155/09-10-2023 (FEK B 5992/13.10.2023).
