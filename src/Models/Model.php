<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models;

use BackedEnum;
use Closure;
use Firebed\AadeMyData\Casts\Arr;
use Firebed\AadeMyData\Casts\Cast;
use Firebed\AadeMyData\Exceptions\InvalidAttributeException;
use JsonSerializable;
use ReflectionClass;
use ReflectionNamedType;

/**
 * Base of every myDATA data type. A model is a bag of attributes, written to and read
 * from XML in the order of $expectedOrder, with the values cast as casts() declares:
 *
 * - a Model class: the attribute holds that model (an array builds one),
 * - a ModelCollection class: a wrapper element with a list inside (e.g. paymentMethods),
 * - a backed enum: the attribute holds a case (an invalid value throws),
 * - Arr::of(...): a list of any of the above or of a scalar; setting a single
 *   value appends it to the list.
 */
abstract class Model implements JsonSerializable
{
    /** @var array<string, mixed> */
    protected array $attributes = [];

    /**
     * The myDATA elements of the model, in XSD order. Read once per class, so set it in
     * the declaration, not per instance.
     *
     * @var list<string>
     */
    protected array $expectedOrder = [];

    /**
     * Booleans written only when true; a false stays on the model. Needs $expectedOrder.
     * List one only after the sandbox shows that myDATA rejects its false (error 214) or
     * stores the same value when it is missing: dropping a false that myDATA stores
     * changes the record.
     *
     * @var list<string>
     */
    protected array $trueOnly = [];

    /** @var array<class-string<static>, array<string, Cast|null>> */
    private static array $resolvedCasts = [];

    /** @var array<class-string<static>, array{array<string, int>, array<string, true>}> */
    private static array $layouts = [];

    /** @var array<class-string<Model>, ReflectionClass<Model>> */
    private static array $reflections = [];

    private static bool $strict = true;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setAttributes($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public static function make(array $attributes = []): static
    {
        return static::fromArray($attributes);
    }

    /**
     * A new model filled from attributes, regardless of the constructor the model
     * declares (some take positional values, e.g. new Location($latitude, $longitude)).
     *
     * @param  array<string, mixed>  $attributes
     */
    public static function fromArray(array $attributes = []): static
    {
        /** @var static $model */
        $model = self::reflection(static::class)->newInstanceWithoutConstructor();

        return $model->setAttributes($attributes);
    }

    /**
     * @param  class-string<Model>  $class
     * @return ReflectionClass<Model>
     */
    private static function reflection(string $class): ReflectionClass
    {
        return self::$reflections[$class] ??= new ReflectionClass($class);
    }

    /**
     * Attribute casts, keyed by attribute name. See the class description for the accepted values.
     *
     * @return array<string, class-string|Arr|'int'|'float'|'string'|'bool'>
     */
    protected function casts(): array
    {
        return [];
    }

    /**
     * Runs the callback with invalid values stored as null instead of throwing, as when
     * myDATA answers with a value this package does not know (e.g. a new invoice type).
     *
     * @template TReturn
     *
     * @param  Closure(): TReturn  $callback
     * @return TReturn
     */
    public static function lenient(Closure $callback): mixed
    {
        $previous = self::$strict;
        self::$strict = false;

        try {
            return $callback();
        } finally {
            self::$strict = $previous;
        }
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->attributes[$key] ?? $default;
    }

    /**
     * Sets an attribute, cast as casts() declares. On a list attribute (Arr::of), an
     * array replaces the list and a single value is appended to it.
     *
     * @throws InvalidAttributeException When the value cannot be cast
     */
    public function set(string $key, mixed $value): static
    {
        $cast = $this->cast($key);

        if (! $cast?->list) {
            $this->attributes[$key] = $this->castValue($key, $value, $cast);

            return $this;
        }

        if ($value === null) {
            $this->attributes[$key] = null;

            return $this;
        }

        if (! is_array($value) || ! array_is_list($value)) {
            return $this->append($key, $value, $cast);
        }

        // Items that cast to nothing (an empty element, an unknown value in lenient mode) are left out
        $items = [];
        foreach ($value as $item) {
            $item = $this->castValue($key, $item, $cast);

            if ($item !== null) {
                $items[] = $item;
            }
        }

        $this->attributes[$key] = $items;

        return $this;
    }

    /**
     * Appends a value to a list attribute. A value that casts to nothing is ignored.
     */
    public function push(string $key, mixed $value): static
    {
        return $this->append($key, $value, $this->cast($key));
    }

    private function append(string $key, mixed $value, ?Cast $cast): static
    {
        $value = $this->castValue($key, $value, $cast);

        if ($value !== null) {
            $this->attributes[$key][] = $value;
        }

        return $this;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->attributes);
    }

    public function unset(string $key): static
    {
        unset($this->attributes[$key]);

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function attributes(): array
    {
        return $this->attributes;
    }

    /**
     * The attributes as they reach the XML, in the order myDATA expects them. Left out:
     * null values, false values of $trueOnly attributes, and, when the order is declared,
     * attributes that are not in $expectedOrder.
     *
     * @return array<string, mixed>
     */
    public function sortedAttributes(): array
    {
        if ($this->expectedOrder === []) {
            return $this->attributes;
        }

        [$positions, $trueOnly] = self::$layouts[static::class] ??= [array_flip($this->expectedOrder), array_fill_keys($this->trueOnly, true)];

        // Walk the attributes, usually far fewer than the declared order, and let
        // array_intersect_key() put the survivors in the declared order.
        $attributes = [];
        foreach ($this->attributes as $key => $value) {
            if ($value !== null && isset($positions[$key]) && ($value !== false || ! isset($trueOnly[$key]))) {
                $attributes[$key] = $value;
            }
        }

        return array_replace(array_intersect_key($positions, $attributes), $attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function setAttributes(array $attributes): static
    {
        foreach ($attributes as $key => $value) {
            $this->set($key, $value);
        }

        return $this;
    }

    /**
     * The resolved cast of an attribute: what casts() declares, else the scalar type of the
     * attribute's getter (<code>getMark(): ?int</code> casts 'mark' to int), else null.
     */
    public function cast(string $key): ?Cast
    {
        $casts = &self::$resolvedCasts[static::class];
        $casts ??= array_map(Cast::resolve(...), $this->casts());

        if (! array_key_exists($key, $casts)) {
            $casts[$key] = $this->getterCast($key);
        }

        return $casts[$key];
    }

    /**
     * The scalar cast implied by the attribute's getter return type, e.g. getIndex(): ?int
     * or isSelfPricing(): ?bool. XML carries every value as text, so this is what turns
     * "12" into 12 without every model listing its scalars.
     */
    private function getterCast(string $key): ?Cast
    {
        $class = self::reflection(static::class);

        foreach (['get'.ucfirst($key), 'is'.ucfirst($key)] as $getter) {
            if (! $class->hasMethod($getter)) {
                continue;
            }

            $type = $class->getMethod($getter)->getReturnType();

            if ($type instanceof ReflectionNamedType && $type->isBuiltin() && in_array($type->getName(), Cast::SCALARS, true)) {
                return Cast::resolve($type->getName());
            }
        }

        return null;
    }

    /**
     * @return list<string>
     */
    public function getExpectedOrder(): array
    {
        return $this->expectedOrder;
    }

    /**
     * @return list<string>
     */
    public function getTrueOnly(): array
    {
        return $this->trueOnly;
    }

    /**
     * The attributes as nested arrays: models become arrays, enums their values.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_map(self::toArrayValue(...), $this->attributes);
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function toJson(int $flags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRESERVE_ZERO_FRACTION): string
    {
        return json_encode($this, $flags | JSON_THROW_ON_ERROR);
    }

    private static function toArrayValue(mixed $value): mixed
    {
        return match (true) {
            $value instanceof Model => $value->toArray(),
            $value instanceof BackedEnum => $value->value,
            is_array($value) => array_map(self::toArrayValue(...), $value),
            default => $value,
        };
    }

    /**
     * Casts one value for an attribute. In lenient mode (Model::lenient()) a value that
     * cannot be cast becomes null instead of throwing.
     */
    protected function castValue(string $key, mixed $value, ?Cast $cast): mixed
    {
        if ($cast === null) {
            // XML carries booleans as text
            return $value === 'true' || $value === 'false' ? $value === 'true' : $value;
        }

        try {
            return $cast->value($key, $value);
        } catch (InvalidAttributeException $e) {
            if (self::$strict) {
                throw $e;
            }

            return null;
        }
    }
}
