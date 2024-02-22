<?php

namespace Firebed\AadeMyData\Models;

/**
 * @extends TypeArray<CancelledInvoice>
 */
class CancelledInvoicesDoc extends TypeArray
{
    protected array $casts = [
        'cancelledInvoice' => CancelledInvoice::class,
    ];

    public function __construct()
    {
        parent::__construct('cancelledInvoice');
    }

    public function offsetGet(mixed $offset): CancelledInvoice
    {
        return $this->attributes['cancelledInvoice'][$offset];
    }
}