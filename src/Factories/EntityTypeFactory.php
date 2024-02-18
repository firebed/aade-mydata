<?php

namespace Firebed\AadeMyData\Factories;

use Firebed\AadeMyData\Enums\EntityTypes;
use Firebed\AadeMyData\Models\EntityType;
use Firebed\AadeMyData\Models\Party;

/**
 * @extends Factory<EntityType>
 */
class EntityTypeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'type'       => fake()->randomElement(EntityTypes::cases())->value,
            'entityData' => Party::factory(),
        ];
    }
}