<?php

namespace Firebed\AadeMyData\Models;

use ReflectionClass;

abstract class Type
{
    protected array $attributes    = [];
    protected array $expectedOrder = [];
    protected array $casts         = [];

    public function get($key, $default = null)
    {
        return $this->attributes[$key] ?? $default;
    }

    public function set($key, $value): void
    {
        $value = $this->castValue($key, $value);

        $this->attributes[$key] = $value;
    }

    protected function castValue(string $key, $value)
    {
        // Auto cast 'true' or 'false' values to boolean
        if ($value === 'true' || $value === 'false') {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }

        $cast = $this->getCast($key);
        if ($cast === null) {
            return $value;
        }

        if ($cast === 'float') {
            return filter_var($value, FILTER_VALIDATE_FLOAT);
        }

        if ($cast === 'int') {
            return filter_var($value, FILTER_VALIDATE_INT);
        }
        
        // Cast value to enum if it is not already an enum
        if ($this->isEnum($key) && !is_object($value)) {
            return $cast::from($value);
        }

        return $value;
    }

    public function push($key, $value = null): void
    {
        $this->attributes[$key][] = $value;
    }

    public function has($key): bool
    {
        return array_key_exists($key, $this->attributes);
    }

    public function attributes(): array
    {
        return $this->attributes;
    }

    public function sortedAttributes(): array
    {
        if (empty($this->expectedOrder)) {
            return $this->attributes;
        }

        $attributes = [];
        foreach ($this->expectedOrder as $key) {
            if (array_key_exists($key, $this->attributes)) {
                $attributes[$key] = $this->attributes[$key];
            }
        }

        return $attributes;
    }

    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }

    public function getCast(string $name)
    {
        return $this->casts[$name] ?? null;
    }

    public function getExpectedOrder(): array
    {
        return $this->expectedOrder;
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public function isEnum($name): bool
    {
        $cast = $this->casts[$name] ?? null;
        if ($cast) {
            return (new ReflectionClass($cast))->isEnum();
        }

        return false;
    }
}