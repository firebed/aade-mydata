<?php

declare(strict_types=1);

namespace Firebed\AadeMyData\Actions;

use Firebed\AadeMyData\Actions\Traits\SummarizesInvoiceTaxes;
use Firebed\AadeMyData\Models\InvoiceSummary;
use Firebed\AadeMyData\Models\TaxesTotals;

class SummarizeInvoiceTaxes
{
    use SummarizesInvoiceTaxes;

    /**
     * @param  array<string, mixed>  $options  Unused, accepted for symmetry with the other summarizers
     */
    public function handle(?TaxesTotals $taxes, array $options = []): void
    {
        if (empty($taxes)) {
            return;
        }

        foreach ($taxes as $tax) {
            $this->addTaxesFromTaxTotals($tax);
        }
    }

    public function saveTotals(InvoiceSummary $summary): void
    {
        $this->saveTaxes($summary);

        $grossValue = $this->round($summary->getTotalGrossValue() + $this->getTotalTaxes());
        $summary->setTotalGrossValue($grossValue);
    }
}
