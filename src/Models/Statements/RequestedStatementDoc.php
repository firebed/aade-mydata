<?php

namespace Firebed\AadeMyData\Models\Statements;

use Firebed\AadeMyData\Models\TypeArray;

/**
 * @extends TypeArray<RequestedStatement>
 *
 * @version 1.0.12
 */
class RequestedStatementDoc extends TypeArray
{
    protected array $casts = [
        'requestedStatement' => RequestedStatement::class,
    ];

    /**
     * @param  RequestedStatement|RequestedStatement[]  $items  Λίστα ζητούμενων δηλώσεων
     */
    public function __construct(RequestedStatement|array $items = [])
    {
        parent::__construct('requestedStatement', $items);
    }

    /**
     * @param  mixed  $offset
     * @return RequestedStatement Αντικείμενο ζητούμενης δήλωσης
     */
    public function offsetGet(mixed $offset): RequestedStatement
    {
        return $this->attributes[$this->childKey][$offset];
    }
}
