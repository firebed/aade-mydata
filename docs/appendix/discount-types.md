# Discount types

How to read the row discount. Enum: `DiscountType`.

```php
use Firebed\AadeMyData\Enums\DiscountType;
use Firebed\AadeMyData\Models\InvoiceDetails;

$row = (new InvoiceDetails())->setDiscount(DiscountType::PERCENTAGE, 10.0); // 10%
```

| Code | Enum | `label()` | English |
|---|---|---|---|
| 1 | `DiscountType::PERCENTAGE` | Ποσοστό | Percentage of the line value |
| 2 | `DiscountType::AMOUNT` | Ποσό | Fixed amount |

> [!NOTE]
> Not part of myDATA. The discount is never written to the myDATA XML. It stays on the model for gateways that transmit through an e-invoicing provider.
