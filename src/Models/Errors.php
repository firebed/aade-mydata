<?php

namespace Firebed\AadeMyData\Models;

/**
 * @extends TypeArray<Error>
 */
class Errors extends TypeArray
{
    protected array $casts = [
        'error' => Error::class,
    ];

    public function __construct()
    {
        parent::__construct('error');
    }

    public function offsetGet(mixed $offset): Error
    {
        return $this->attributes['error'][$offset];
    }
}