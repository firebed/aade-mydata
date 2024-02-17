<?php

namespace Tests\Xml;

use ArrayAccess;
use Countable;

class Node implements Countable, ArrayAccess
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

    public function get(string $name): mixed
    {
        $value = $this->xml[$name];

        if (!is_array($value)) {
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

    public function offsetSet($offset, $value): void
    {
    }

    public function offsetUnset($offset): void
    {
    }
}