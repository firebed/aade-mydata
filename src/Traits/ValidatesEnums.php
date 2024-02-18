<?php

namespace Firebed\AadeMyData\Traits;

use BackedEnum;

trait ValidatesEnums
{
    public function isEnum($type): bool
    {
        return $type instanceof BackedEnum;
    }
}