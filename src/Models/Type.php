<?php

namespace Firebed\AadeMyData\Models;

use BackedEnum;
use IteratorAggregate;

abstract class Type
{
    protected array $attributes    = [];
    protected array $expectedOrder = [];

    public function get($key, $default = null)
    {
        return $this->attributes[$key] ?? $default;
    }

    public function set($key, $value): void
    {
        if ($value instanceof BackedEnum) {
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

    public function getExpectedOrder(): array
    {
        return $this->expectedOrder;
    }
}