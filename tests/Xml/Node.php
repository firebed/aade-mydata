<?php

declare(strict_types=1);

namespace Tests\Xml;

use ArrayAccess;
use Countable;

class Node implements ArrayAccess, Countable
{
    private array $xml;

    public function __construct(array $xml)
    {
        $this->xml = $xml;
    }

    public function count(): int
    {
        return count($this->xml);
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __isset($name): bool
    {
        return isset($this->xml[$name]);
    }

    public function get(string|int $name): mixed
    {
        if (! isset($this->xml[$name])) {
            return null;
        }

        $value = $this->xml[$name];

        if (! is_array($value)) {
            return $value;
        }

        return new self($this->xml[$name]);
    }

    public function offsetExists($offset): bool
    {
        return isset($this->xml[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value): void {}

    public function offsetUnset($offset): void {}
}
