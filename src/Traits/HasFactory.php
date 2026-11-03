<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Traits;

use Firebed\AadeMyData\Factories\Factory;

trait HasFactory
{
    /**
     * @return Factory<static>
     */
    public static function factory(int $count = 1): Factory
    {
        return Factory::factoryForModel(static::class, $count);
    }
}
