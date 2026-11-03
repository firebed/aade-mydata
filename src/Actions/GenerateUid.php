<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Actions;

use Firebed\AadeMyData\Enums\InvoiceType;
use Firebed\AadeMyData\Enums\InvoiceVariationType;

class GenerateUid
{
    public function handle(string $vatNumber, string $issueDate, ?int $branchId, InvoiceType|string $invoiceType, string $series, int|string $number, InvoiceVariationType|int|null $invoiceVariationType = null): string
    {
        $attributes = [
            $vatNumber,
            $issueDate,
            $branchId ?? 0,
            $invoiceType instanceof InvoiceType ? $invoiceType->value : $invoiceType,
            $this->normalizeSeries($series),
            $number,
        ];

        if ($invoiceVariationType) {
            $attributes[] = $invoiceVariationType instanceof InvoiceVariationType ? $invoiceVariationType->value : $invoiceVariationType;
        }

        return strtoupper(sha1(implode('-', $attributes)));
    }

    /**
     * Converts series to iso-8859-7 to match myData's current format.
     */
    private function normalizeSeries(string $series): string
    {
        return mb_convert_encoding($series, 'ISO-8859-7', 'UTF-8');
    }
}
