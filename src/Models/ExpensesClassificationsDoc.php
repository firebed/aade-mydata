<?php

namespace Firebed\AadeMyData\Models;

/**
 * @extends TypeArray<ExpensesClassification>
 */
class ExpensesClassificationsDoc extends TypeArray
{
    protected array $casts = [
        'expensesInvoiceClassification' => InvoiceExpensesClassification::class,
    ];

    public function __construct()
    {
        parent::__construct('expensesInvoiceClassification');
    }

    public function offsetGet($offset): ExpensesClassification
    {
        return $this->attributes['expensesInvoiceClassification'][$offset];
    }
}