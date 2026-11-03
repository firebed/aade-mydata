<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Enums\EntityTypes;
use Firebed\AadeMyData\Traits\HasFactory;

/**
 * @version 1.0.7
 */
class EntityType extends Model
{
    use HasFactory;

    protected array $expectedOrder = [
        'type',
        'entityData',
    ];

    protected function casts(): array
    {
        return [
            'type' => EntityTypes::class,
            'entityData' => Party::class,
        ];
    }

    /**
     * @return EntityTypes|null Κατηγορία Οντότητας
     *
     * @version 1.0.7
     */
    public function getType(): ?EntityTypes
    {
        return $this->get('type');
    }

    /**
     * Κατηγορία Οντότητας
     *
     * @param  EntityTypes|int  $type  Κατηγορία Οντότητας
     *
     * @version 1.0.7
     */
    public function setType(EntityTypes|int $type): static
    {
        return $this->set('type', $type);
    }

    /**
     * @version 1.0.7
     */
    public function getEntityData(): Party
    {
        return $this->get('entityData');
    }

    /**
     * @version 1.0.7
     */
    public function setEntityData(Party $entityData): static
    {
        return $this->set('entityData', $entityData);
    }
}
