<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Exceptions;

use InvalidArgumentException;

/**
 * A value set on a model attribute cannot be cast to what the model declares
 * for it in casts(): an unknown enum value, a scalar where a model is expected, …
 */
class InvalidAttributeException extends InvalidArgumentException
{
    public static function invalidEnum(string $key, string $enum, mixed $value): self
    {
        return new self(sprintf("%s is not a valid %s for attribute '%s'.", self::describe($value), $enum, $key));
    }

    public static function invalidType(string $key, string $type, mixed $value): self
    {
        return new self(sprintf("Attribute '%s' expects %s, %s given.", $key, $type, self::describe($value)));
    }

    private static function describe(mixed $value): string
    {
        return match (true) {
            is_scalar($value) => var_export($value, true),
            is_object($value) => $value::class,
            default => get_debug_type($value),
        };
    }
}
