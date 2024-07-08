<?php

namespace Firebed\AadeMyData\Models;

/**
 * @extends  TypeArray<IncomeClassification>
 */
class IncomeClassificationsDoc extends TypeArray
{
    protected array $casts = [
        'incomeInvoiceClassification' => InvoiceIncomeClassification::class,
    ];

    /**
     * @param InvoiceIncomeClassification|InvoiceIncomeClassification[] $classifications
     */
    public function __construct(InvoiceIncomeClassification|array $classifications = [])
    {
        parent::__construct('incomeClassificationsDoc', $classifications);
    }

    public function offsetGet($offset): IncomeClassification
    {
        return $this->attributes['incomeClassificationsDoc'][$offset];
    }
}