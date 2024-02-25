<?php

namespace Firebed\AadeMyData\Actions;

use Firebed\AadeMyData\Actions\Traits\SummarizesInvoiceTaxes;
use Firebed\AadeMyData\Models\InvoiceSummary;
use Firebed\AadeMyData\Models\TaxesTotals;

class SummarizeInvoiceTaxes
{
    use SummarizesInvoiceTaxes;

    /**
     * @param TaxesTotals|null $taxes
     * @return void
     */
    public function handle(?TaxesTotals $taxes): void
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

        $grossValue = $this->round($summary->getTotalGrossValue() - $this->getTotalTaxes());
        $summary->setTotalGrossValue($grossValue);
    }
}