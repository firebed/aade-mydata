<?php

namespace Firebed\AadeMyData\Traits;

use Firebed\AadeMyData\Factories\Factory;

trait HasFactory
{
    /**
     * @return Factory<static>
     */
    public static function factory(): Factory
    {
        return Factory::factoryForModel(get_called_class());
    }

}