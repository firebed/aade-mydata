# ECRToken

`ECRToken` is the signature of a POS payment when you send the invoice from an ERP. It belongs to a
[PaymentMethodDetail](payment-method-detail-type) with type `PaymentType::POS`.

```php
use Firebed\AadeMyData\Models\ECRToken;

$ecrToken = new ECRToken('ABC1234567', '123456');

// or
$ecrToken = new ECRToken();
$ecrToken->setSigningAuthor('ABC1234567');
$ecrToken->setSessionNumber('123456');
```

| Field         | Type     | Required | Description                                          |
|---------------|----------|----------|------------------------------------------------------|
| SigningAuthor | `string` | Yes      | ECR id: registry number of the fiscal device (max 15) |
| SessionNumber | `string` | Yes      | Unique 6-digit code of the transaction               |

Details of `SessionNumber` in decision A.1155/09-10-2023 (FEK B 5992/13.10.2023).
