# Τύπος EntityType

Ο τύπος `EntityType` αναφέρεται στις λοιπές συσχετιζόμενες οντότητες του παραστατικού.

## Το αντικείμενο αυτό περιέχει τα εξής πεδία:

| Πεδίο                                | Υποχρεωτικό | Περιγραφή                     |
|--------------------------------------|-------------|-------------------------------|
| [**type**](../appendix/entity-types) | **Ναι**     | Κατηγορία Οντότητας           |
| [**entityData**](party-type)         | **Ναι**     | Στοιχεία Οντότητας (Προσώπου) |

## Παραδείγματα

```php
use Firebed\AadeMyData\Models\EntityType;
use Firebed\AadeMyData\Models\Party;
use Firebed\AadeMyData\Enums\EntityTypes;

$entity = new EntityType();
$entity->setType(EntityTypes::TYPE_3); // Μεταφορέας

$party = new Party();
$party->setVatNumber('123456789');
$party->setCountry('GR');
$party->setBranch(0);
$entity->setEntityData($party);
```