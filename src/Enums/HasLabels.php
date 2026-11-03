<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Enums;

trait HasLabels
{
    abstract public function label(): string;

    public static function labels(): array
    {
        return array_map(fn (self $case) => [$case->value => $case->label()], self::cases());
    }
}
