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

    public function set($key, $value): static
    {
        $value = $this->castValue($key, $value);

        $this->attributes[$key] = $value;
        return $this;
    }

    protected function castValue(string $key, $value)
    {
        if ($value === null) {
            return null;
        }
        
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
        // If the value doesn't correspond to an enum, it
        // will return null.
        if ($this->isEnum($key) && !is_object($value)) {
            return $cast::tryFrom($value);
        }

        return $value;
    }

    public function push($key, $value = null): static
    {
        $this->attributes[$key][] = $value;
        return $this;
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

    public function setAttributes(array $attributes): static
    {
        $this->attributes = $attributes;
        return $this;
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

    /**
     * Returns an array representation of the object
     * 
     * @return array
     */
    public function toArray(): array
    {
        $array = [];
        foreach ($this->attributes() as $key => $value) {
            if ($value instanceof Type) {
                $array[$key] = $value->toArray();
            } elseif (is_array($value)) {
                $array[$key] = array_map(fn($v) => $v instanceof Type ? $v->toArray() : $v, $value);
            } elseif ($this->isEnum($key) && is_object($value)) {
                $array[$key] = $value->value;
            } else {
                $array[$key] = $value;
            }
        }
        
        return $array;
    }
}