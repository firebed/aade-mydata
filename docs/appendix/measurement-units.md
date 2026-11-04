# Measurement units

Unit of the row quantity (`measurementUnit`). Enum: `UnitMeasurement`.

```php
use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Enums\UnitMeasurement;
use Firebed\AadeMyData\Models\InvoiceDetails;

$row = (new InvoiceDetails())
    ->setQuantity(2.5)
    ->setMeasurementUnit(UnitMeasurement::KILOGRAMS);

// Does the invoice type take a unit?
InvoiceType::TYPE_2_1->supportsUnitOfMeasurement(); // false
```

| Code | Enum | `label()` | English |
|---|---|---|---|
| 1 | `UnitMeasurement::PIECES` | Τεμάχια | Pieces |
| 2 | `UnitMeasurement::KILOGRAMS` | Κιλά | Kilograms |
| 3 | `UnitMeasurement::LITERS` | Λίτρα | Liters |
| 4 | `UnitMeasurement::METERS` | Μέτρα | Meters |
| 5 | `UnitMeasurement::SQUARE_METERS` | Τετραγωνικά Μέτρα | Square meters |
| 6 | `UnitMeasurement::CUBIC_METERS` | Κυβικά Μέτρα | Cubic meters |
| 7 | `UnitMeasurement::PIECES_OTHER_CASES` | Τεμάχια_Λοιπές Περιπτώσεις | Pieces, other cases |
