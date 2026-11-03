<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Casts;

use BackedEnum;
use Firebed\AadeMyData\Enums\TryFromName;
use Firebed\AadeMyData\Exceptions\InvalidAttributeException;
use Firebed\AadeMyData\Models\Model;
use Firebed\AadeMyData\Models\ModelCollection;
use ReflectionEnum;

/**
 * A resolved entry of Model::casts(): what one attribute (or each item of a list
 * attribute) is cast to, and how. Casting is strict: a value that does not fit
 * throws InvalidAttributeException, and Model decides whether to swallow that.
 *
 * The scalar type names are also available as constants, for completion in casts():
 * <code>Arr::of(Cast::INT)</code> is the same as <code>Arr::of('int')</code>.
 * Instances are resolved by Model from the declarations and are not built by hand.
 */
final class Cast
{
    public const string INT = 'int';

    public const string FLOAT = 'float';

    public const string STRING = 'string';

    public const string BOOL = 'bool';

    public const array SCALARS = [self::INT, self::FLOAT, self::STRING, self::BOOL];

    /** @var array<string, self> */
    private static array $resolved = [];

    public readonly Kind $kind;

    private readonly bool $intBacked;

    private readonly bool $triesName;

    /**
     * @param  class-string|'int'|'float'|'string'|'bool'  $type
     */
    private function __construct(public readonly string $type, public readonly bool $list)
    {
        $this->kind = match (true) {
            in_array($type, self::SCALARS, true) => Kind::Scalar,
            is_subclass_of($type, BackedEnum::class) => Kind::Enum,
            is_subclass_of($type, ModelCollection::class) => Kind::Collection,
            is_subclass_of($type, Model::class) => Kind::Model,
            default => throw new InvalidAttributeException("Unknown cast type '$type'. Expected a Model, a ModelCollection, a backed enum, or one of int, float, string, bool."),
        };

        $this->intBacked = is_subclass_of($type, BackedEnum::class) && (string) (new ReflectionEnum($type))->getBackingType() === 'int';
        $this->triesName = is_subclass_of($type, TryFromName::class);
    }

    /**
     * @param  class-string|Arr|'int'|'float'|'string'|'bool'  $declaration  An entry of Model::casts()
     */
    public static function resolve(string|Arr $declaration): self
    {
        [$type, $list] = $declaration instanceof Arr ? [$declaration->type, true] : [$declaration, false];

        return self::$resolved[$type.($list ? '[]' : '')] ??= new self($type, $list);
    }

    public function isEnum(): bool
    {
        return $this->kind === Kind::Enum;
    }

    public function isModel(): bool
    {
        return $this->kind === Kind::Model;
    }

    public function isCollection(): bool
    {
        return $this->kind === Kind::Collection;
    }

    public function isScalar(): bool
    {
        return $this->kind === Kind::Scalar;
    }

    /**
     * A new, empty instance of the model or collection this cast targets, null for enums and scalars.
     */
    public function newModel(): ?Model
    {
        /** @var class-string<Model> $type */
        $type = $this->type;

        return match ($this->kind) {
            Kind::Collection => new $type,
            Kind::Model => $type::fromArray(),
            default => null,
        };
    }

    /**
     * Casts one value (one item, for a list cast). Null and '' mean no value.
     *
     * @throws InvalidAttributeException When the value cannot be cast (e.g. an unknown enum value)
     */
    public function value(string $key, mixed $value): mixed
    {
        if ($value === null || $value === '') {
            return null;
        }

        return match ($this->kind) {
            Kind::Scalar => $this->castScalar($key, $value),
            Kind::Enum => $this->castEnum($key, $value),
            Kind::Model => $this->castModel($key, $value),
            Kind::Collection => $this->castCollection($value),
        };
    }

    private function castScalar(string $key, mixed $value): mixed
    {
        // The common case: XML text into a string attribute
        if ($this->type === self::STRING && is_string($value)) {
            return $value;
        }

        if ($value instanceof BackedEnum) {
            $value = $value->value;
        }

        if (is_object($value) || is_array($value)) {
            throw InvalidAttributeException::invalidType($key, $this->type, $value);
        }

        $cast = match ($this->type) {
            self::INT => is_int($value) ? $value : filter_var($value, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE),
            self::FLOAT => is_float($value) ? $value : filter_var($value, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE),
            self::BOOL => is_bool($value) ? $value : filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            default => is_bool($value) ? ($value ? 'true' : 'false') : (string) $value,
        };

        return $cast ?? throw InvalidAttributeException::invalidType($key, $this->type, $value);
    }

    private function castEnum(string $key, mixed $value): BackedEnum
    {
        if ($value instanceof BackedEnum && $value instanceof $this->type) {
            return $value;
        }

        /** @var class-string<BackedEnum> $enum */
        $enum = $this->type;

        if (is_int($value) || is_string($value)) {
            if ($this->intBacked) {
                // Only a whole number is a candidate: '7.9' is not case 7
                $int = is_int($value) ? $value : filter_var($value, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                $case = $int === null ? null : $enum::tryFrom($int);
            } else {
                $case = $enum::tryFrom((string) $value);
            }

            if ($case === null && $this->triesName) {
                /** @var class-string<BackedEnum&TryFromName> $enum */
                $case = $enum::tryFromName((string) $value);
            }

            if ($case !== null) {
                return $case;
            }
        }

        throw InvalidAttributeException::invalidEnum($key, $enum, $value);
    }

    private function castModel(string $key, mixed $value): Model
    {
        if ($value instanceof Model && $value instanceof $this->type) {
            return $value;
        }

        if (is_array($value)) {
            /** @var class-string<Model> $model */
            $model = $this->type;

            return $model::fromArray($value);
        }

        throw InvalidAttributeException::invalidType($key, $this->type, $value);
    }

    /**
     * @return ModelCollection<mixed>
     */
    private function castCollection(mixed $value): ModelCollection
    {
        if ($value instanceof ModelCollection && $value instanceof $this->type) {
            return $value;
        }

        /** @var class-string<ModelCollection<mixed>> $collection */
        $collection = $this->type;

        return new $collection($value);
    }
}
