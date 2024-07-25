<?php

namespace Firebed\AadeMyData\Enums;

trait HasLabels
{
    public abstract function label(): string;
    
    public static function labels(): array
    {
        return array_map(fn(self $case) => [$case->value => $case->label()], self::cases());
    }
}