<?php

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Traits\ValidatesEnums;
use IteratorAggregate;

abstract class Type
{
    use ValidatesEnums;

    protected array $attributes = [];

    public function get($key, $default = null)
    {
        return $this->attributes[$key] ?? $default;
    }

    public function put($key, $value): void
    {
        if ($this->isEnum($value)) {
            $value = $value->value;
        }

        if ($this instanceof IteratorAggregate) {
            $this->attributes[] = $value;
            return;
        }

        $this->attributes[$key] = $value;
    }

    public function push($key, $value = null): void
    {
        $array = $this->get($key, []);

        $array[] = $value;

        $this->attributes[$key] = $array;
    }

    public function has($key): bool
    {
        return array_key_exists($key, $this->attributes);
    }

    public function attributes(): array
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }
}