<?php

namespace Firebed\AadeMyData\Traits;

trait ValidatesEnums
{
    public function isEnum($type): bool
    {
        return is_object($type)
            && function_exists('enum_exists')
            && enum_exists($class = get_class($type))
            && method_exists($class, 'tryFrom');
    }
}