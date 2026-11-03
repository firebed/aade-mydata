<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Enums;

/**
 * An enum that can also be resolved from a case name, for the places where myDATA
 * answers with a name instead of the backing value. The casts fall back to it when
 * tryFrom() finds no case.
 */
interface TryFromName
{
    public static function tryFromName(string $name): ?static;
}
