<?php

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Enums\EntityTypes;
use Firebed\AadeMyData\Traits\HasFactory;

/**
 * @version 1.0.7
 */
class EntityType extends Type
{
    use HasFactory;
    
    protected array $expectedOrder = [
        'type',
        'entityData',
    ];
    
    /**
     * @return EntityTypes|int Κατηγορία Οντότητας
     *
     * @version 1.0.7
     */
    public function getType(): EntityTypes|int
    {
        return $this->get('type');
    }

    /**
     * Κατηγορία Οντότητας
     *
     * @param EntityTypes|int $type Κατηγορία Οντότητας
     *
     * @version 1.0.7
     */
    public function setType(EntityTypes|int $type): void
    {
        $this->set('type', $type);
    }

    /**
     * @return Party
     *
     * @version 1.0.7
     */
    public function getEntityData(): Party
    {
        return $this->get('entityData');
    }

    /**
     * @param  Party  $entityData
     *
     * @version 1.0.7
     */
    public function setEntityData(Party $entityData): void
    {
        $this->set('entityData', $entityData);
    }
}