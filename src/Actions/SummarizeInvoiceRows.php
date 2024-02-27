<?php

namespace Firebed\AadeMyData\Actions;

use Firebed\AadeMyData\Actions\Traits\SummarizesInvoiceTaxes;
use Firebed\AadeMyData\Models\InvoiceDetails;
use Firebed\AadeMyData\Models\InvoiceSummary;

class SummarizeInvoiceRows
{
    use SummarizesInvoiceTaxes;

    public float $totalNetValue  = 0;
    public float $totalVatAmount = 0;

    /**
     * @param InvoiceDetails[]|null $rows
     * @param array $options
     * @return void
     */
    public function handle(?array $rows, array $options = []): void
    {
        if (empty($rows)) {
            return;
        }

        foreach ($rows as $row) {
            $this->totalNetValue += abs($row->getNetValue() ?? 0);
            $this->totalVatAmount += abs($row->getVatAmount() ?? 0);
            
            $this->addTaxesFromInvoiceRow($row);
        }
    }

    public function saveTotals(InvoiceSummary $summary): void
    {
        $netValue = $this->round($summary->getTotalNetValue() + $this->totalNetValue);
        $summary->setTotalNetValue($netValue);

        $vatAmount = $this->round($summary->getTotalVatAmount() + $this->totalVatAmount);
        $summary->setTotalVatAmount($vatAmount);

        $this->saveTaxes($summary);
        
        $grossValue = $this->round($summary->getTotalGrossValue() + $this->getTotalGrossValue());
        $summary->setTotalGrossValue($grossValue);
    }

    public function getTotalGrossValue(): float
    {
        return $this->totalNetValue + $this->totalVatAmount - $this->getTotalTaxes();
    }
}