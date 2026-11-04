# EntityType

`EntityType` is another entity related to the invoice, e.g. the carrier. Add it to the
[InvoiceHeader](invoice-header-type) with `addOtherCorrelatedEntities()`.

```php
use Firebed\AadeMyData\Enums\EntityTypes;
use Firebed\AadeMyData\Models\EntityType;
use Firebed\AadeMyData\Models\Party;

$carrier = new Party();
$carrier->setVatNumber('123456789');
$carrier->setCountry('GR');
$carrier->setBranch(0);

$entity = new EntityType();
$entity->setType(EntityTypes::CARRIER);
$entity->setEntityData($carrier);

$header->addOtherCorrelatedEntities($entity);
```

| Field      | Type                                         | Required | Description          |
|------------|----------------------------------------------|----------|----------------------|
| type       | [`EntityTypes`](../appendix/entity-types)    | Yes      | Entity category      |
| entityData | [Party](party-type)                          | Yes      | Entity details       |
