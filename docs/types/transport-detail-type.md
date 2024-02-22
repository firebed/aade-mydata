# Τύπος TransportDetail - Πληροφορίες Λοιπών Μεταφορικών Μέσων

## Το αντικείμενο αυτό περιέχει τα εξής πεδία:

| Πεδίο           | Υποχρεωτικό | Περιγραφή                          |
|-----------------|-------------|------------------------------------|
| vehicleNumber   | **Ναι**     | Αριθμός Μεταφορικού Μέσου (max 50) |

```php
use Firebed\AadeMyData\Models\TransportDetail;

$transport = new TransportDetail();
$transport->setVehicleNumber('AHB 7799');
```