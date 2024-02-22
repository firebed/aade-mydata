<?php

namespace Firebed\AadeMyData\Models;

/**
 * @extends  TypeArray<IncomeClassification>
 */
class IncomeClassificationsDoc extends TypeArray
{
    protected array $casts = [
        'incomeClassificationsDoc' => InvoiceIncomeClassification::class,
    ];

    public function __construct()
    {
        parent::__construct('incomeClassificationsDoc');
    }

    public function offsetGet($offset): IncomeClassification
    {
        return $this->attributes['incomeClassificationsDoc'][$offset];
    }
}