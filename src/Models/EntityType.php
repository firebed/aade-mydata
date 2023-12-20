<?php

namespace Firebed\AadeMyData\Models;

/**
 * @version 1.0.7
 */
class EntityType extends Type
{
    /**
     * @return int Κατηγορία Οντότητας
     *
     * @version 1.0.7
     */
    public function getType(): int
    {
        return $this->get('type');
    }

    /**
     * Κατηγορία Οντότητας
     * <ol>
     *     <li>Φορολογικός Εκπρόσωπος</li>
     *     <li>Διαμεσολαβητής</li>
     * </ol>
     * 
     * @param  int  $type Κατηγορία Οντότητας
     *
     * @version 1.0.7
     */
    public function setType(int $type): void
    {
        $this->put('type', $type);
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
        $this->put('entityData', $entityData);
    }
}