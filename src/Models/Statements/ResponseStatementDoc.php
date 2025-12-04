<?php

namespace Firebed\AadeMyData\Models\Statements;

use Firebed\AadeMyData\Models\TypeArray;

/**
 * @extends TypeArray<ResponseStatement>
 *
 * @version 1.0.12
 */
class ResponseStatementDoc extends TypeArray
{
    protected array $casts = [
        'response' => ResponseStatement::class,
    ];

    public function __construct()
    {
        parent::__construct('response');
    }

    public function offsetGet(mixed $offset): ResponseStatement
    {
        return $this->attributes['response'][$offset];
    }
}