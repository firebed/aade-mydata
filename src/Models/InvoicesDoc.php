<?php

namespace Firebed\AadeMyData\Models;

/**
 * @extends TypeArray<Invoice>
 */
class InvoicesDoc extends TypeArray
{
    protected array $casts = [
        'invoice' => Invoice::class,
    ];

    /**
     * @param Invoice|Invoice[] $invoices
     */
    public function __construct(Invoice|array $invoices = [])
    {
        parent::__construct('invoice', $invoices);
    }
    
    public function offsetGet(mixed $offset): Invoice
    {
        return $this->attributes[$this->childKey][$offset];
    }
}