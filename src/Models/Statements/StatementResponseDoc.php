<?php

namespace Firebed\AadeMyData\Models\Statements;

use Firebed\AadeMyData\Models\TypeArray;

/**
 * @extends TypeArray<StatementResponse>
 *
 * @version 1.0.12
 */
class StatementResponseDoc extends TypeArray
{
    protected array $casts = [
        'response' => StatementResponse::class,
    ];

    public function __construct()
    {
        parent::__construct('response');
    }

    public function offsetGet(mixed $offset): StatementResponse
    {
        return $this->attributes['response'][$offset];
    }
}