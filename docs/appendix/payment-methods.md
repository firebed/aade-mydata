# Payment methods

Payment method type. Enum: `PaymentType`.

```php
use Firebed\AadeMyData\Enums\PaymentType;
use Firebed\AadeMyData\Models\Invoice;
use Firebed\AadeMyData\Models\PaymentMethodDetail;

$invoice = new Invoice();
$invoice->addPaymentMethod(
    (new PaymentMethodDetail())->setType(PaymentType::CASH)->setAmount(124.0)
);
```

| Code | Enum | `label()` | English |
|---|---|---|---|
| 1 | `PaymentType::DOMESTIC_PAYMENTS_ACCOUNT` | Επαγ. Λογαριασμός Πληρωμών Ημεδαπής | Domestic business payment account |
| 2 | `PaymentType::FOREIGN_PAYMENTS_ACCOUNT` | Επαγ. Λογαριασμός Πληρωμών Αλλοδαπής | Foreign business payment account |
| 3 | `PaymentType::CASH` | Μετρητά | Cash |
| 4 | `PaymentType::CHEQUE` | Επιταγή | Cheque |
| 5 | `PaymentType::ON_CREDIT` | Επί Πιστώσει | On credit |
| 6 | `PaymentType::WEB_BANKING` | Web Banking | Web banking |
| 7 | `PaymentType::POS` | POS / e-POS | POS / e-POS |
| 8 | `PaymentType::IRIS` | Άμεσες Πληρωμές IRIS | IRIS instant payments |
