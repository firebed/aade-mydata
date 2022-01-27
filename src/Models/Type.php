<?php

namespace Firebed\AadeMyData\Models;

abstract class Type
{
    protected array $attributes = [];

    public function put($key, $value): self
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    public function get($key, $default = null)
    {
        return $this->attributes[$key] ?? $default;
    }

    public function has($key): bool
    {
        return array_key_exists($key, $this->attributes);
    }

    public function properties(): array
    {
        return $this->attributes;
    }
}