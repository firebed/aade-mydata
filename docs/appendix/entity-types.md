# Entity types

Role of a related entity on the invoice. Enum: `EntityTypes`. Used in [EntityType](../models/entity-type).

```php
use Firebed\AadeMyData\Enums\CountryCode;
use Firebed\AadeMyData\Enums\EntityTypes;
use Firebed\AadeMyData\Models\EntityType;
use Firebed\AadeMyData\Models\InvoiceHeader;
use Firebed\AadeMyData\Models\Party;

$carrier = (new Party())
    ->setVatNumber('123456789')
    ->setCountry(CountryCode::GR)
    ->setBranch(0);

$header = (new InvoiceHeader())->addOtherCorrelatedEntities(
    (new EntityType())->setType(EntityTypes::CARRIER)->setEntityData($carrier)
);
```

| Code | Enum | `label()` | English |
|---|---|---|---|
| 1 | `EntityTypes::TAX_REPRESENTATIVE` | Φορολογικός Εκπρόσωπος | Tax representative |
| 2 | `EntityTypes::INTERMEDIARY` | Διαμεσολαβητής | Intermediary |
| 3 | `EntityTypes::CARRIER` | Μεταφορέας | Carrier |
| 4 | `EntityTypes::RECIPIENT_OF_SENDER` | Λήπτης του Αποστολέα (Πωλητή) | Recipient of the sender (seller) |
| 5 | `EntityTypes::SENDER` | Αποστολέας (Πωλητής) | Sender (seller) |
| 6 | `EntityTypes::OTHER_RELATED_ENTITIES` | Λοιπές Συσχετιζόμενες Οντότητες | Other related entities |
