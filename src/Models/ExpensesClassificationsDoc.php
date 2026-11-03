<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Models;

use Firebed\AadeMyData\Casts\Arr;

/**
 * @extends ModelCollection<InvoiceExpensesClassification>
 */
class ExpensesClassificationsDoc extends ModelCollection
{
    protected function casts(): array
    {
        return [
            'expensesInvoiceClassification' => Arr::of(InvoiceExpensesClassification::class),
        ];
    }
}
