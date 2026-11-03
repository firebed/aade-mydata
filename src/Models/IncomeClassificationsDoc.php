<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Casts\Arr;

/**
 * @extends ModelCollection<InvoiceIncomeClassification>
 */
class IncomeClassificationsDoc extends ModelCollection
{
    protected function casts(): array
    {
        return [
            'incomeInvoiceClassification' => Arr::of(InvoiceIncomeClassification::class),
        ];
    }
}
