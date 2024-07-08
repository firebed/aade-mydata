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

    /**
     * @param  InvoiceExpensesClassification|InvoiceExpensesClassification[]  $classifications
     */
    public function __construct(InvoiceExpensesClassification|array $classifications = [])
    {
        parent::__construct('expensesInvoiceClassification', $classifications);
    }

    public function offsetGet($offset): ExpensesClassification
    {
        return $this->attributes['expensesInvoiceClassification'][$offset];
    }
}